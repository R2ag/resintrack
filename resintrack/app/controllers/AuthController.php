<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../core/Controller.php';

class AuthController extends Controller {

    public function login() {
        // se já estiver logado, manda para dashboard
        if (isset($_SESSION['usuario'])) {
            header("Location: " . BASE_URL . "index.php?page=dashboard");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $usuarioModel = new Usuario();
            $usuario = $usuarioModel->buscarPorEmail($_POST['email']);

            // Debug: verificar se usuário existe
            if (!$usuario) {
                $erro = "Email ou senha inválidos! [Usuário não encontrado]";
                $this->view("auth/login", compact('erro'));
                return;
            }

            // Debug: testar password_verify
            if (!password_verify($_POST['senha'], $usuario['senha'])) {
                $erro = "Email ou senha inválidos! [Hash não combina]";
                $this->view("auth/login", compact('erro'));
                return;
            }

            // Se chegou aqui, autenticação OK
            $_SESSION['usuario'] = [
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'perfil_id' => $usuario['perfil_id']
            ];

            // autenticado: redireciona para o dashboard
            header("Location: " . BASE_URL . "index.php?page=dashboard");
            exit;
        }

        $this->view("auth/login");
    }
}