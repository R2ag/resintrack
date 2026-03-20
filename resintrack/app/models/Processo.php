<?php
require_once __DIR__ . '/../core/Model.php';

class Processo extends Model
{
    public function listar()
    {
        $sql = "SELECT * FROM processos ORDER BY nome ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function criar($dados)
    {
        $sql = "INSERT INTO processos (codigo, nome, descricao) VALUES (:codigo, :nome, :descricao)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':codigo', $dados['codigo']);
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->bindValue(':descricao', $dados['descricao']);
        $stmt->execute();
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT * FROM processos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $dados)
    {
        $sql = "UPDATE processos SET codigo = :codigo, nome = :nome, descricao = :descricao WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':codigo', $dados['codigo']);
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->bindValue(':descricao', $dados['descricao']);
        $stmt->execute();
    }

    public function deletar($id)
    {
        $sql = "DELETE FROM processos WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
    }
}
?>