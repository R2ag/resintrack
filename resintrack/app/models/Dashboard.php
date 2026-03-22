<?php
require_once __DIR__ . '/../core/Model.php';

class Dashboard extends Model
{
    private function consumoSubquery()
    {
        return "SELECT
                    lote_id,
                    SUM(consumo_total) as consumo_total
                FROM (
                    -- Consumo por processos
                    SELECT
                        ei.lote_id,
                        SUM(ei.quantidade_por_chapa * ep.qtd_chapas)/1000 AS consumo_total
                    FROM execucoes_insumos ei
                    JOIN execucoes_processos ep ON ep.id = ei.execucao_id
                    GROUP BY ei.lote_id

                    UNION ALL

                    -- Saídas manuais
                    SELECT
                        lote_id,
                        SUM(quantidade) as consumo_total
                    FROM lotes_saidas
                    GROUP BY lote_id
                ) consumos
                GROUP BY lote_id";
    }

    private function ultimaPesagemSubquery()
    {
        return "SELECT p1.lote_id, p1.peso_apurado
                FROM pesagens p1
                INNER JOIN (
                    SELECT lote_id, MAX(data_pesagem) AS max_data
                    FROM pesagens
                    GROUP BY lote_id
                ) p2 
                    ON p1.lote_id = p2.lote_id 
                AND p1.data_pesagem = p2.max_data";
    }

    public function totalInsumos()
    {
        return $this->db->query("SELECT COUNT(*) FROM insumos")
            ->fetchColumn();
    }

    public function lotesAtivos()
    {
        return $this->db->query("SELECT COUNT(*) FROM lotes WHERE data_fim IS NULL")
            ->fetchColumn();
    }

    public function saldoTotal()
    {
        return $this->db->query("SELECT COALESCE(SUM(saldo_atual),0) FROM lotes")
            ->fetchColumn();
    }

    public function consumoMedioPorChapa()
    {
        $sql = "SELECT 
                (SUM(ei.quantidade_por_chapa) / SUM(ep.qtd_chapas))/1000 AS media
                FROM execucoes_insumos ei
                JOIN execucoes_processos ep ON ep.id = ei.execucao_id";

        return $this->db->query($sql)->fetchColumn() ?? 0;
    }

    public function percentualPerda()
    {
        $sql = "SELECT
                    CASE
                        WHEN SUM(l.peso_inicial) > 0 THEN
                            SUM(
                                CASE
                                    WHEN p.peso_apurado IS NOT NULL THEN
                                        -- Se há pesagem: perda = (peso_inicial - consumo) - (peso_apurado - tara)
                                        (l.peso_inicial - COALESCE(c.consumo_total, 0)) - (p.peso_apurado - l.tara)
                                    ELSE
                                        0 -- Sem pesagem, não há perda calculável
                                END
                            ) * 100 / SUM(l.peso_inicial)
                        ELSE 0
                    END as percentual_perda
                FROM lotes l
                LEFT JOIN ({$this->ultimaPesagemSubquery()}) p ON p.lote_id = l.id
                LEFT JOIN ({$this->consumoSubquery()}) c ON c.lote_id = l.id
                WHERE p.peso_apurado IS NOT NULL"; // Só calcula perda para lotes que têm pesagem

        return $this->db->query($sql)->fetchColumn() ?? 0;
    }

    public function topLotesConsumo()
    {
        $sql = "SELECT l.lote,
                    c.consumo_total as total
                FROM ({$this->consumoSubquery()}) c
                JOIN lotes l ON l.id = c.lote_id
                ORDER BY total DESC
                LIMIT 5";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consumoMensal()
{
    $sql = "SELECT 
                DATE_FORMAT(ep.data_execucao, '%m/%Y') as mes,
                SUM(ei.quantidade_por_chapa * ep.qtd_chapas)/1000 as total
            FROM execucoes_insumos ei
            JOIN execucoes_processos ep ON ep.id = ei.execucao_id
            WHERE ep.data_execucao >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
            GROUP BY DATE_FORMAT(ep.data_execucao, '%m/%Y')
            ORDER BY MIN(ep.data_execucao) DESC";

    return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}

    public function insumosPorConsumo()
    {
        $sql = "SELECT i.descricao as nome,
                    COALESCE(SUM(l.peso_inicial - l.saldo_atual), 0) as consumido
                FROM insumos i
                LEFT JOIN lotes l ON l.insumo_id = i.id
                GROUP BY i.id
                ORDER BY consumido DESC
                LIMIT 8";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function statusLotes()
    {
        $sql = "SELECT 
                    SUM(data_fim IS NULL) as ativos,
                    SUM(data_fim IS NOT NULL) as finalizados,
                    COUNT(*) as total
                FROM lotes";

        return $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function eficienciaConsumoPorChapa()
    {
        $sql = "SELECT 
                    DATE_FORMAT(ep.data_execucao, '%m/%Y') as mes,
                    (SUM(ei.quantidade_por_chapa) / SUM(ep.qtd_chapas))/1000 as consumo_medio
                FROM execucoes_insumos ei
                JOIN execucoes_processos ep ON ep.id = ei.execucao_id
                WHERE ep.data_execucao >= DATE_SUB(CURDATE(), INTERVAL 12 MONTH)
                GROUP BY DATE_FORMAT(ep.data_execucao, '%m/%Y')
                ORDER BY MIN(ep.data_execucao)";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obterInsumos()
    {
        return $this->db->query("SELECT id, descricao FROM insumos ORDER BY descricao")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    public function obterLotes()
    {
        $sql = "SELECT l.id, l.lote, i.descricao as insumo
                FROM lotes l
                LEFT JOIN insumos i ON i.id = l.insumo_id
                ORDER BY l.lote";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function perdasPorLote($insumo_id = null)
    {
        $sql = "SELECT 
                    l.id,
                    l.lote,
                    i.descricao AS insumo,
                    l.peso_inicial,
                    l.tara,
                    COALESCE(p.peso_apurado, 0) AS peso_apurado,
                    COALESCE(c.consumo_total, 0) AS consumo_total,

                    (COALESCE(p.peso_apurado, 0) - l.tara) AS saldo_real,
                    (l.peso_inicial - COALESCE(c.consumo_total, 0)) AS saldo_teorico,

                    (
                        (l.peso_inicial - COALESCE(c.consumo_total, 0)) -
                        (COALESCE(p.peso_apurado, 0) - l.tara)
                    ) AS perda,

                    CASE 
                        WHEN l.peso_inicial > 0 THEN
                            (
                                (l.peso_inicial - COALESCE(c.consumo_total, 0)) -
                                (COALESCE(p.peso_apurado, 0) - l.tara)
                            ) * 100 / l.peso_inicial
                        ELSE 0
                    END AS percentual_perda

                FROM lotes l
                LEFT JOIN insumos i ON i.id = l.insumo_id
                LEFT JOIN ({$this->ultimaPesagemSubquery()}) p ON p.lote_id = l.id
                LEFT JOIN ({$this->consumoSubquery()}) c ON c.lote_id = l.id";

        if ($insumo_id) {
            $sql .= " WHERE l.insumo_id = :insumo_id";
        }

        $sql .= " ORDER BY l.lote";

        $stmt = $this->db->prepare($sql);

        if ($insumo_id) {
            $stmt->bindValue(':insumo_id', $insumo_id, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function perdasPorInsumo()
    {
        $sql = "SELECT 
                    i.id,
                    i.descricao AS insumo,
                    COUNT(l.id) AS total_lotes,
                    SUM(l.peso_inicial) AS peso_total,
                    SUM(c.consumo_total) AS consumo_total,

                    SUM(
                        (l.peso_inicial - COALESCE(c.consumo_total,0)) -
                        (COALESCE(p.peso_apurado,0) - l.tara)
                    ) AS perda_total,

                    CASE 
                        WHEN SUM(l.peso_inicial) > 0 THEN
                            SUM(
                                (l.peso_inicial - COALESCE(c.consumo_total,0)) -
                                (COALESCE(p.peso_apurado,0) - l.tara)
                            ) * 100 / SUM(l.peso_inicial)
                        ELSE 0
                    END AS percentual_perda

                FROM insumos i
                LEFT JOIN lotes l ON l.insumo_id = i.id
                LEFT JOIN ({$this->consumoSubquery()}) c ON c.lote_id = l.id
                LEFT JOIN ({$this->ultimaPesagemSubquery()}) p ON p.lote_id = l.id
                GROUP BY i.id
                ORDER BY percentual_perda DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function consumoDiario(){
        $sql = "SELECT 
                    DATE_FORMAT(ep.data_execucao, '%d/%m/%Y') as data,
                    SUM(ei.quantidade_por_chapa * ep.qtd_chapas)/1000 as consumo_total
                FROM execucoes_insumos ei
                JOIN execucoes_processos ep ON ep.id = ei.execucao_id
                WHERE ep.data_execucao >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                GROUP BY DATE_FORMAT(ep.data_execucao, '%d/%m/%Y') -- Alterado para coincidir com o SELECT
                ORDER BY MIN(ep.data_execucao)";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function perdaDiaria()
    {
        $sql = "SELECT 
                    DATE_FORMAT(p.data_pesagem, '%d/%m/%Y') as data,
                    SUM(
                        (l.peso_inicial - COALESCE(c.consumo_total,0)) -
                        (p.peso_apurado - l.tara)
                    ) as perda_diaria
                FROM pesagens p
                JOIN lotes l ON l.id = p.lote_id
                LEFT JOIN ({$this->consumoSubquery()}) c ON c.lote_id = l.id
                WHERE p.data_pesagem >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                GROUP BY DATE_FORMAT(p.data_pesagem, '%d/%m/%Y') -- Alterado para coincidir
                ORDER BY MIN(p.data_pesagem)";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}