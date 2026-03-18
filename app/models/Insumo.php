<?php
require_once __DIR__ . '/../core/Model.php';

class Insumo extends Model {

    public function listar() {
        $sql = $this->db->query("SELECT * FROM insumos ORDER BY id DESC");
        return $sql->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscar($id) {
        $sql = $this->db->prepare("SELECT * FROM insumos WHERE id = :id");
        $sql->bindValue(":id", $id);
        $sql->execute();
        return $sql->fetch(PDO::FETCH_ASSOC);
    }

    public function criar($descricao) {
        $sql = $this->db->prepare("INSERT INTO insumos (descricao) VALUES (:descricao)");
        $sql->bindValue(":descricao", $descricao);
        return $sql->execute();
    }

    public function atualizar($id, $descricao) {
        $sql = $this->db->prepare("UPDATE insumos SET descricao = :descricao WHERE id = :id");
        $sql->bindValue(":descricao", $descricao);
        $sql->bindValue(":id", $id);
        return $sql->execute();
    }

    public function excluir($id) {
        // Verifica se possui lotes vinculados
        $verifica = $this->db->prepare("SELECT COUNT(*) FROM lotes WHERE insumo_id = :id");
        $verifica->bindValue(":id", $id);
        $verifica->execute();

        if ($verifica->fetchColumn() > 0) {
            return false;
        }

        $sql = $this->db->prepare("DELETE FROM insumos WHERE id = :id");
        $sql->bindValue(":id", $id);
        return $sql->execute();
    }
}