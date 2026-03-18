<?php
require_once __DIR__ . '/../core/Model.php';

class Material extends Model
{
    public function listar()
    {
        $sql = "SELECT * FROM materiais ORDER BY nome ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar($dados)
    {
        $sql = "INSERT INTO materiais (nome) VALUES (:nome)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->execute();
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM materiais WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $dados)
    {
        $sql = "UPDATE materiais SET nome = :nome WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->execute();
    }

    public function deletar($id)
    {
        $sql = "DELETE FROM materiais WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
?>