<?php
require_once __DIR__ . '/../core/Model.php';

class Bloco extends Model
{
    public function listar()
    {
        $sql = "SELECT b.*, m.nome as material_nome
                FROM blocos b
                JOIN materiais m ON m.id = b.material_id
                ORDER BY b.codigo ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar($dados)
    {
        $sql = "INSERT INTO blocos (codigo, material_id, numero_chapas) VALUES (:codigo, :material_id, :numero_chapas)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':codigo', $dados['codigo']);
        $stmt->bindValue(':material_id', $dados['material_id']);
        $stmt->bindValue(':numero_chapas', $dados['numero_chapas']);
        $stmt->execute();
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT b.*, m.nome as material_nome
                FROM blocos b
                JOIN materiais m ON m.id = b.material_id
                WHERE b.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $dados)
    {
        $sql = "UPDATE blocos SET codigo = :codigo, material_id = :material_id, numero_chapas = :numero_chapas WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':codigo', $dados['codigo']);
        $stmt->bindValue(':material_id', $dados['material_id']);
        $stmt->bindValue(':numero_chapas', $dados['numero_chapas']);
        $stmt->execute();
    }

    public function deletar($id)
    {
        $sql = "DELETE FROM blocos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
?>