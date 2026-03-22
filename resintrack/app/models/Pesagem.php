<?php
require_once __DIR__ . '/../core/Model.php';

class Pesagem extends Model
{
    public function listar()
    {
        $sql = "SELECT p.*, l.lote 
                FROM pesagens p
                JOIN lotes l ON l.id = p.lote_id
                ORDER BY p.data_pesagem DESC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar($dados)
    {
        $sql = "INSERT INTO pesagens (lote_id, data_pesagem, peso_apurado)
                VALUES (:lote_id, :data_pesagem, :peso_apurado)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':lote_id', $dados['lote_id']);
        $stmt->bindValue(':data_pesagem', $dados['data_pesagem']);
        $stmt->bindValue(':peso_apurado', $dados['peso_apurado']);
        $stmt->execute();

        // Atualiza saldo do lote
        $this->atualizarSaldoLote($dados['lote_id'], $dados['peso_apurado']);
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT p.*, l.lote 
                FROM pesagens p
                JOIN lotes l ON l.id = p.lote_id
                WHERE p.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $dados)
    {
        // Buscar pesagem antiga para calcular diferença
        $sqlAntiga = "SELECT peso_apurado, lote_id FROM pesagens WHERE id = :id";
        $stmtAntiga = $this->db->prepare($sqlAntiga);
        $stmtAntiga->bindValue(':id', $id);
        $stmtAntiga->execute();
        $pesagemAntiga = $stmtAntiga->fetch(PDO::FETCH_ASSOC);

        $sql = "UPDATE pesagens SET lote_id = :lote_id, data_pesagem = :data_pesagem, peso_apurado = :peso_apurado WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':lote_id', $dados['lote_id']);
        $stmt->bindValue(':data_pesagem', $dados['data_pesagem']);
        $stmt->bindValue(':peso_apurado', $dados['peso_apurado']);
        $stmt->execute();

        // Atualizar saldo do lote antigo se foi alterado o lote
        if ($pesagemAntiga['lote_id'] != $dados['lote_id']) {
            $this->atualizarSaldoLote($pesagemAntiga['lote_id'], $pesagemAntiga['peso_apurado']);
        }

        // Atualiza saldo do lote novo
        $this->atualizarSaldoLote($dados['lote_id'], $dados['peso_apurado']);
    }

    public function deletar($id)
    {
        // Buscar dados da pesagem para recalcular saldo
        $sqlPesagem = "SELECT lote_id, peso_apurado FROM pesagens WHERE id = :id";
        $stmtPesagem = $this->db->prepare($sqlPesagem);
        $stmtPesagem->bindValue(':id', $id);
        $stmtPesagem->execute();
        $pesagem = $stmtPesagem->fetch(PDO::FETCH_ASSOC);

        // Deletar pesagem
        $sql = "DELETE FROM pesagens WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        // Recalcular saldo do lote
        if ($pesagem) {
            $this->atualizarSaldoLote($pesagem['lote_id'], 0);
        }
    }

    private function atualizarSaldoLote($lote_id, $peso_apurado)
    {
        require_once __DIR__ . '/Lote.php';
        $loteModel = new Lote();
        $loteModel->recalcularSaldo($lote_id);
    }
}