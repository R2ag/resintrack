<?php
require_once __DIR__ . '/../core/Model.php';

class Lote extends Model
{

    private function ensureSaidasTable()
    {
        $this->db->exec("CREATE TABLE IF NOT EXISTS lotes_saidas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            lote_id INT NOT NULL,
            quantidade DECIMAL(12,3) NOT NULL,
            data_saida DATE NOT NULL,
            motivo VARCHAR(255) DEFAULT NULL,
            criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT fk_lotes_saidas_lote FOREIGN KEY (lote_id) REFERENCES lotes(id) ON DELETE CASCADE ON UPDATE CASCADE
        )");
    }

    public function listar()
    {

        $sql = $this->db->query("
            SELECT l.*, i.descricao AS insumo_nome
            FROM lotes l
            JOIN insumos i ON i.id = l.insumo_id
            ORDER BY l.id DESC
        ");

        $lotes = $sql->fetchAll(PDO::FETCH_ASSOC);

        foreach ($lotes as &$l) {
            $l = $this->calcularIndicadores($l);
        }

        return $lotes;
    }

    public function buscar($id)
    {
        $sql = $this->db->prepare("SELECT * FROM lotes WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function getEntradas($lote_id)
    {
        $sql = $this->db->prepare("
            SELECT *
            FROM lotes_entradas
            WHERE lote_id = :lote_id
            ORDER BY data_entrada ASC
        ");
        $sql->bindValue(":lote_id", $lote_id);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSaidas($lote_id)
    {
        $this->ensureSaidasTable();

        $sql = $this->db->prepare("
            SELECT *
            FROM lotes_saidas
            WHERE lote_id = :lote_id
            ORDER BY data_saida ASC
        ");
        $sql->bindValue(":lote_id", $lote_id);
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function adicionarEntrada($lote_id, $quantidade, $data_entrada)
    {
        // Iniciar transação
        $this->db->beginTransaction();

        try {
            // Inserir nova entrada
            $sql = $this->db->prepare("
                INSERT INTO lotes_entradas (lote_id, quantidade, data_entrada)
                VALUES (:lote_id, :quantidade, :data_entrada)
            ");
            $sql->execute([
                ':lote_id' => $lote_id,
                ':quantidade' => $quantidade,
                ':data_entrada' => $data_entrada
            ]);

            // Atualizar peso_inicial do lote (soma de todas as entradas)
            $sql_total = $this->db->prepare("
                SELECT SUM(quantidade) as total
                FROM lotes_entradas
                WHERE lote_id = :lote_id
            ");
            $sql_total->execute([':lote_id' => $lote_id]);
            $total = $sql_total->fetchColumn();

            $sql_update = $this->db->prepare("
                UPDATE lotes
                SET peso_inicial = :peso_inicial
                WHERE id = :lote_id
            ");
            $sql_update->execute([
                ':peso_inicial' => $total,
                ':lote_id' => $lote_id
            ]);

            $this->db->commit();

            // Recalcular saldo
            $this->recalcularSaldo($lote_id);

            return true;

        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function adicionarSaida($lote_id, $quantidade, $data_saida, $motivo = null)
    {
        $this->ensureSaidasTable();

        // validação básica
        if ($quantidade <= 0) {
            return false;
        }

        // verificar saldo disponível
        $lote = $this->buscar($lote_id);
        if (!$lote || $lote['saldo_atual'] < $quantidade) {
            return false;
        }

        // inserir saída
        $sql = $this->db->prepare("\n            INSERT INTO lotes_saidas (lote_id, quantidade, data_saida, motivo)
            VALUES (:lote_id, :quantidade, :data_saida, :motivo)
        ");
        $ok = $sql->execute([
            ':lote_id' => $lote_id,
            ':quantidade' => $quantidade,
            ':data_saida' => $data_saida,
            ':motivo' => $motivo
        ]);

        if ($ok) {
            $this->recalcularSaldo($lote_id);
        }

        return $ok;
    }

    public function criar($dados)
    {
        // ao inserir um novo lote, define saldo_atual igual ao peso inicial
        $sql = $this->db->prepare("
            INSERT INTO lotes 
            (lote, insumo_id, peso_inicial, tara, data_entrada, saldo_atual)
            VALUES (:lote, :insumo_id, :peso_inicial, :tara, :data_entrada, :saldo_atual)
        ");

        // o parâmetro saldo_atual é o próprio peso inicial
        $dados[':saldo_atual'] = $dados[':peso_inicial'];

        $ok = $sql->execute($dados);

        if ($ok) {
            $lote_id = $this->db->lastInsertId();

            // Registrar a primeira entrada
            $sql_entrada = $this->db->prepare("
                INSERT INTO lotes_entradas (lote_id, quantidade, data_entrada)
                VALUES (:lote_id, :quantidade, :data_entrada)
            ");
            $sql_entrada->execute([
                ':lote_id' => $lote_id,
                ':quantidade' => $dados[':peso_inicial'],
                ':data_entrada' => $dados[':data_entrada']
            ]);
        }

        return $ok;
    }

    public function listarAtivos()
    {
        $sql = "SELECT l.*, i.descricao AS insumo_nome
            FROM lotes l
            JOIN insumos i ON i.id = l.insumo_id
            WHERE l.data_fim IS NULL
            ORDER BY l.data_entrada DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizar($dados)
    {
        $sql = $this->db->prepare("
            UPDATE lotes 
            SET lote = :lote,
                insumo_id = :insumo_id,
                peso_inicial = :peso_inicial,
                tara = :tara,
                data_entrada = :data_entrada,
                data_fim = :data_fim
            WHERE id = :id
        ");

        $ok = $sql->execute($dados);

        if ($ok) {
            // após alteração, recalcule o saldo atual
            $this->recalcularSaldo($dados[':id']);
        }

        return $ok;
    }

    private function calcularIndicadores($lote)
    {
        $this->ensureSaidasTable();

        // Consumo total registrado (processos + saídas manuais)
        $sql = $this->db->prepare("
            SELECT SUM(ei.quantidade_por_chapa * ep.qtd_chapas) / 1000
            FROM execucoes_insumos ei
            JOIN execucoes_processos ep ON ep.id = ei.execucao_id
            WHERE ei.lote_id = :id
        ");
        $sql->bindValue(":id", $lote['id']);
        $sql->execute();

        $consumoProcessos = $sql->fetchColumn() ?? 0;

        $sql = $this->db->prepare("
            SELECT COALESCE(SUM(quantidade), 0) as total_saida
            FROM lotes_saidas
            WHERE lote_id = :id
        ");
        $sql->bindValue(":id", $lote['id']);
        $sql->execute();

        $consumoManual = $sql->fetchColumn() ?? 0;

        $consumo_total = $consumoProcessos + $consumoManual;

        // Saldo teórico: o que deveria restar baseado nos registros
        $saldo_teorico = $lote['peso_inicial'] - $consumo_total;

        // Última pesagem física
        $sql = $this->db->prepare("
            SELECT peso_apurado
            FROM pesagens
            WHERE lote_id = :id
            ORDER BY data_pesagem DESC
            LIMIT 1
        ");
        $sql->bindValue(":id", $lote['id']);
        $sql->execute();

        $ultima_pesagem = $sql->fetchColumn();

        if ($ultima_pesagem !== false) {
            // Saldo real: o que realmente restou na pesagem física
            $saldo_real = $ultima_pesagem - $lote['tara'];
            // Perda/ganho: diferença entre o que deveria restar e o que realmente restou
            $perda = $saldo_teorico - $saldo_real;
        } else {
            // Sem pesagem, saldo real = saldo teórico
            $saldo_real = $saldo_teorico;
            $perda = 0;
        }

        $lote['consumo_total'] = $consumo_total;
        $lote['saldo_teorico'] = $saldo_teorico;
        $lote['saldo_real'] = $saldo_real;
        $lote['perda'] = $perda;

        return $lote;
    }

    /**
     * Recalcula campo saldo_atual do lote a partir dos dados mais recentes
     * O saldo_atual representa o saldo disponível para consumo futuro
     */
    public function recalcularSaldo($lote_id)
    {
        // buscar tara e peso_inicial
        $sql = $this->db->prepare("SELECT peso_inicial, tara FROM lotes WHERE id = :id");
        $sql->bindValue(':id', $lote_id);
        $sql->execute();
        $lote = $sql->fetch(PDO::FETCH_ASSOC);

        if (!$lote) {
            return;
        }

        // total consumido por processos
        $sql = $this->db->prepare("SELECT SUM(ei.quantidade_por_chapa * ep.qtd_chapas) AS total FROM execucoes_insumos ei JOIN execucoes_processos ep ON ep.id = ei.execucao_id WHERE ei.lote_id = :id");
        $sql->bindValue(':id', $lote_id);
        $sql->execute();
        $consumo = $sql->fetch(PDO::FETCH_ASSOC);
        $totalConsumido = ($consumo['total'] ?? 0) / 1000;

        $this->ensureSaidasTable();

        // total saídas manuais
        $sql = $this->db->prepare("SELECT COALESCE(SUM(quantidade), 0) AS total_saida FROM lotes_saidas WHERE lote_id = :id");
        $sql->bindValue(':id', $lote_id);
        $sql->execute();
        $totalSaidaManual = $sql->fetchColumn() ?? 0;

        // última pesagem apurada
        $sql = $this->db->prepare("SELECT peso_apurado FROM pesagens WHERE lote_id = :id ORDER BY data_pesagem DESC LIMIT 1");
        $sql->bindValue(':id', $lote_id);
        $sql->execute();
        $ultima = $sql->fetchColumn();

        // Lógica do saldo_atual:
        // Se há pesagem, o saldo_atual é o saldo real (pesagem - tara)
        // Se não há pesagem, o saldo_atual é o saldo teórico (peso_inicial - consumos)
        if ($ultima !== false) {
            $saldo = $ultima - $lote['tara'];
        } else {
            $saldo = $lote['peso_inicial'] - $totalConsumido - $totalSaidaManual;
        }

        $sql = $this->db->prepare("UPDATE lotes SET saldo_atual = :saldo WHERE id = :id");
        $sql->bindValue(':saldo', $saldo);
        $sql->bindValue(':id', $lote_id);
        $sql->execute();
    }

    public function deletar($id)
    {
        // Deletar todas as entradas parceladas
        $sql = $this->db->prepare("DELETE FROM lotes_entradas WHERE lote_id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        // Deletar todas as pesagens associadas ao lote
        $sql = $this->db->prepare("DELETE FROM pesagens WHERE lote_id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        // Deletar todas as execuções de insumos associadas ao lote
        $sql = $this->db->prepare("DELETE FROM execucoes_insumos WHERE lote_id = :id");
        $sql->bindValue(':id', $id);
        $sql->execute();

        // Deletar o lote
        $sql = $this->db->prepare("DELETE FROM lotes WHERE id = :id");
        $sql->bindValue(':id', $id);
        return $sql->execute();
    }
}
