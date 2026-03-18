<?php
require_once __DIR__ . '/../core/Model.php';

class Usuario extends Model
{
    public function listar()
    {
        $sql = "SELECT u.*, p.nome as perfil_nome
                FROM usuarios u
                LEFT JOIN perfis p ON p.id = u.perfil_id
                ORDER BY u.nome ASC";

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $sql = "SELECT u.*, p.nome as perfil_nome
                FROM usuarios u
                LEFT JOIN perfis p ON p.id = u.perfil_id
                WHERE u.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorEmail($email)
    {
        $sql = "SELECT * FROM usuarios WHERE email = :email LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar($dados)
    {
        // Validar se email já existe
        if ($this->buscarPorEmail($dados['email'])) {
            throw new Exception('Email já cadastrado');
        }

        $sql = "INSERT INTO usuarios (nome, email, senha, perfil_id, ativo)
                VALUES (:nome, :email, :senha, :perfil_id, :ativo)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->bindValue(':email', $dados['email']);
        $stmt->bindValue(':senha', password_hash($dados['senha'], PASSWORD_BCRYPT));
        $stmt->bindValue(':perfil_id', $dados['perfil_id']);
        $stmt->bindValue(':ativo', isset($dados['ativo']) ? 1 : 0);

        return $stmt->execute();
    }

    public function atualizar($id, $dados)
    {
        // Validar se email já existe (exceto o do próprio usuário)
        $usuarioExistente = $this->buscarPorEmail($dados['email']);
        if ($usuarioExistente && $usuarioExistente['id'] != $id) {
            throw new Exception('Email já cadastrado');
        }

        // Se não houver senha, mantém a atual
        if (!empty($dados['senha'])) {
            $sql = "UPDATE usuarios 
                    SET nome = :nome, email = :email, senha = :senha, perfil_id = :perfil_id, ativo = :ativo
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':senha', password_hash($dados['senha'], PASSWORD_BCRYPT));
        } else {
            $sql = "UPDATE usuarios 
                    SET nome = :nome, email = :email, perfil_id = :perfil_id, ativo = :ativo
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
        }

        $stmt->bindValue(':id', $id);
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->bindValue(':email', $dados['email']);
        $stmt->bindValue(':perfil_id', $dados['perfil_id']);
        $stmt->bindValue(':ativo', isset($dados['ativo']) ? 1 : 0);

        return $stmt->execute();
    }

    public function deletar($id)
    {
        // Não deletar se for o único admin
        $sql = "SELECT COUNT(*) as total FROM usuarios WHERE perfil_id = 1";
        $result = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);

        if ($result['total'] == 1) {
            $usuario = $this->buscarPorId($id);
            if ($usuario['perfil_id'] == 1) {
                throw new Exception('Não é possível deletar o único administrador');
            }
        }

        $sql = "DELETE FROM usuarios WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function obterPerfis()
    {
        $sql = "SELECT * FROM perfis ORDER BY nome ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}

