<?php
require_once __DIR__ . '/../core/Model.php';

class ExecucaoProcesso extends Model
{
    public function listar()
    {
        $sql = "SELECT ep.*, p.nome as processo_nome, p.codigo as processo_codigo, b.codigo as bloco_codigo, m.nome as material_nome
                FROM execucoes_processos ep
                JOIN processos p ON p.id = ep.processo_id
                JOIN blocos b ON b.id = ep.bloco_id
                JOIN materiais m ON m.id = b.material_id
                ORDER BY ep.data_execucao DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar($dados)
    {
        // Iniciar transação
        $this->db->beginTransaction();

        try {
            // Inserir execução do processo
            $sql = "INSERT INTO execucoes_processos
                    (processo_id, bloco_id, data_execucao, qtd_chapas)
                    VALUES
                    (:processo_id, :bloco_id, :data_execucao, :qtd_chapas)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':processo_id', $dados['processo_id']);
            $stmt->bindValue(':bloco_id', $dados['bloco_id']);
            $stmt->bindValue(':data_execucao', $dados['data_execucao']);
            $stmt->bindValue(':qtd_chapas', $dados['qtd_chapas']);
            $stmt->execute();

            $execucao_id = $this->db->lastInsertId();

            // Inserir insumos utilizados
            if (isset($dados['insumos']) && is_array($dados['insumos'])) {
                foreach ($dados['insumos'] as $insumo) {
                    if (!empty($insumo['lote_id']) && !empty($insumo['quantidade_por_chapa'])) {
                        $sqlInsumo = "INSERT INTO execucoes_insumos
                                      (execucao_id, lote_id, quantidade_por_chapa)
                                      VALUES
                                      (:execucao_id, :lote_id, :quantidade_por_chapa)";

                        $stmtInsumo = $this->db->prepare($sqlInsumo);
                        $stmtInsumo->bindValue(':execucao_id', $execucao_id);
                        $stmtInsumo->bindValue(':lote_id', $insumo['lote_id']);
                        $stmtInsumo->bindValue(':quantidade_por_chapa', $insumo['quantidade_por_chapa']);
                        $stmtInsumo->execute();

                        // Atualizar saldo do lote
                        $this->atualizarSaldo($insumo['lote_id'], $dados['qtd_chapas'] * $insumo['quantidade_por_chapa']);
                    }
                }
            }

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT ep.*, p.nome as processo_nome, p.codigo as processo_codigo, b.codigo as bloco_codigo, m.nome as material_nome
                FROM execucoes_processos ep
                JOIN processos p ON p.id = ep.processo_id
                JOIN blocos b ON b.id = ep.bloco_id
                JOIN materiais m ON m.id = b.material_id
                WHERE ep.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $execucao = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($execucao) {
            // Buscar insumos
            $sqlInsumos = "SELECT ei.*, l.lote, i.descricao as insumo_nome
                           FROM execucoes_insumos ei
                           JOIN lotes l ON l.id = ei.lote_id
                           JOIN insumos i ON i.id = l.insumo_id
                           WHERE ei.execucao_id = :execucao_id";

            $stmtInsumos = $this->db->prepare($sqlInsumos);
            $stmtInsumos->bindValue(':execucao_id', $id);
            $stmtInsumos->execute();
            $execucao['insumos'] = $stmtInsumos->fetchAll(PDO::FETCH_ASSOC);
        }

        return $execucao;
    }

    public function atualizar($id, $dados)
    {
        // Buscar execução antiga para recalcular saldos
        $execucaoAntiga = $this->buscarPorId($id);

        // Iniciar transação
        $this->db->beginTransaction();

        try {
            // Atualizar execução
            $sql = "UPDATE execucoes_processos
                    SET processo_id = :processo_id, bloco_id = :bloco_id, data_execucao = :data_execucao, qtd_chapas = :qtd_chapas
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':processo_id', $dados['processo_id']);
            $stmt->bindValue(':bloco_id', $dados['bloco_id']);
            $stmt->bindValue(':data_execucao', $dados['data_execucao']);
            $stmt->bindValue(':qtd_chapas', $dados['qtd_chapas']);
            $stmt->execute();

            // Deletar insumos antigos e recalcular saldos
            $sqlDelete = "DELETE FROM execucoes_insumos WHERE execucao_id = :execucao_id";
            $stmtDelete = $this->db->prepare($sqlDelete);
            $stmtDelete->bindValue(':execucao_id', $id);
            $stmtDelete->execute();

            // Recalcular saldos dos lotes antigos
            foreach ($execucaoAntiga['insumos'] as $insumo) {
                $this->recalcularSaldoLote($insumo['lote_id']);
            }

            // Inserir novos insumos
            if (isset($dados['insumos']) && is_array($dados['insumos'])) {
                foreach ($dados['insumos'] as $insumo) {
                    if (!empty($insumo['lote_id']) && !empty($insumo['quantidade_por_chapa'])) {
                        $sqlInsumo = "INSERT INTO execucoes_insumos
                                      (execucao_id, lote_id, quantidade_por_chapa)
                                      VALUES
                                      (:execucao_id, :lote_id, :quantidade_por_chapa)";

                        $stmtInsumo = $this->db->prepare($sqlInsumo);
                        $stmtInsumo->bindValue(':execucao_id', $id);
                        $stmtInsumo->bindValue(':lote_id', $insumo['lote_id']);
                        $stmtInsumo->bindValue(':quantidade_por_chapa', $insumo['quantidade_por_chapa']);
                        $stmtInsumo->execute();

                        // Atualizar saldo do lote
                        $this->atualizarSaldo($insumo['lote_id'], $dados['qtd_chapas'] * $insumo['quantidade_por_chapa']);
                    }
                }
            }

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function deletar($id)
    {
        // Buscar execução para recalcular saldos
        $execucao = $this->buscarPorId($id);

        // Iniciar transação
        $this->db->beginTransaction();

        try {
            // Deletar insumos
            $sqlDeleteInsumos = "DELETE FROM execucoes_insumos WHERE execucao_id = :execucao_id";
            $stmtDeleteInsumos = $this->db->prepare($sqlDeleteInsumos);
            $stmtDeleteInsumos->bindValue(':execucao_id', $id);
            $stmtDeleteInsumos->execute();

            // Deletar execução
            $sqlDelete = "DELETE FROM execucoes_processos WHERE id = :id";
            $stmtDelete = $this->db->prepare($sqlDelete);
            $stmtDelete->bindValue(':id', $id);
            $stmtDelete->execute();

            // Recalcular saldos dos lotes
            foreach ($execucao['insumos'] as $insumo) {
                $this->recalcularSaldoLote($insumo['lote_id']);
            }

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function recalcularSaldoLote($lote_id)
    {
        // Buscar tara e peso apurado
        $sqlLote = "SELECT tara FROM lotes WHERE id = :id";
        $stmtLote = $this->db->prepare($sqlLote);
        $stmtLote->bindValue(':id', $lote_id);
        $stmtLote->execute();
        $lote = $stmtLote->fetch(PDO::FETCH_ASSOC);

        // Buscar última pesagem
        $sqlPesagem = "SELECT peso_apurado FROM pesagens WHERE lote_id = :id ORDER BY data_pesagem DESC LIMIT 1";
        $stmtPesagem = $this->db->prepare($sqlPesagem);
        $stmtPesagem->bindValue(':id', $lote_id);
        $stmtPesagem->execute();
        $pesagem = $stmtPesagem->fetch(PDO::FETCH_ASSOC);
        $pesoApurado = $pesagem['peso_apurado'] ?? 0;

        // Somar consumo total
        $sqlConsumo = "SELECT SUM(ei.quantidade_por_chapa * ep.qtd_chapas) AS total
                       FROM execucoes_insumos ei
                       JOIN execucoes_processos ep ON ep.id = ei.execucao_id
                       WHERE ei.lote_id = :id";
        $stmtConsumo = $this->db->prepare($sqlConsumo);
        $stmtConsumo->bindValue(':id', $lote_id);
        $stmtConsumo->execute();
        $consumo = $stmtConsumo->fetch(PDO::FETCH_ASSOC);
        $totalConsumido = ($consumo['total'] ?? 0) / 1000;

        $saldo = $pesoApurado - $lote['tara'] - $totalConsumido;

        $sqlUpdate = "UPDATE lotes SET saldo_atual = :saldo WHERE id = :id";
        $stmtUpdate = $this->db->prepare($sqlUpdate);
        $stmtUpdate->bindValue(':saldo', $saldo);
        $stmtUpdate->bindValue(':id', $lote_id);
        $stmtUpdate->execute();
    }

    private function atualizarSaldo($lote_id, $consumoTotal)
    {
        require_once __DIR__ . '/Lote.php';
        $loteModel = new Lote();
        $loteModel->recalcularSaldo($lote_id);
    }
}
?>