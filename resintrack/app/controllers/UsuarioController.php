<?php
require_once __DIR__ . '/../models/Usuario.php';
require_once __DIR__ . '/../core/Controller.php';

class UsuarioController extends Controller
{
    public function index()
    {
        $this->proteger();

        // Verificar se é admin
        $usuario = $_SESSION['usuario'] ?? null;
        if ($usuario['perfil_id'] != 1) {
            header("Location: " . BASE_URL . "index.php?page=dashboard");
            exit;
        }

        $model = new Usuario();
        $usuarios = $model->listar();

        $this->view('usuarios/index', compact('usuarios'));
    }

    public function create()
    {
        $this->proteger();

        // Verificar se é admin
        $usuario = $_SESSION['usuario'] ?? null;
        if ($usuario['perfil_id'] != 1) {
            header("Location: " . BASE_URL . "index.php?page=dashboard");
            exit;
        }

        $model = new Usuario();
        $perfis = $model->obterPerfis();

        $this->view('usuarios/create', compact('perfis'));
    }

    public function store()
    {
        $this->proteger();

        // Verificar se é admin
        $usuario = $_SESSION['usuario'] ?? null;
        if ($usuario['perfil_id'] != 1) {
            header("Location: " . BASE_URL . "index.php?page=dashboard");
            exit;
        }

        // Validação
        $required = ['nome', 'email', 'senha', 'perfil_id'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $model = new Usuario();
                $perfis = $model->obterPerfis();
                $erro = 'Todos os campos são obrigatórios';
                $old = $_POST;
                $this->view('usuarios/create', compact('perfis', 'erro', 'old'));
                return;
            }
        }

        // Validar email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $model = new Usuario();
            $perfis = $model->obterPerfis();
            $erro = 'Email inválido';
            $old = $_POST;
            $this->view('usuarios/create', compact('perfis', 'erro', 'old'));
            return;
        }

        $model = new Usuario();
        try {
            $model->criar($_POST);

            header("Location: " . BASE_URL . "index.php?page=usuarios");
            exit;
        } catch (Exception $e) {
            $perfis = $model->obterPerfis();
            $erro = $e->getMessage();
            $old = $_POST;
            $this->view('usuarios/create', compact('perfis', 'erro', 'old'));
        }
    }

    public function edit()
    {
        $this->proteger();

        // Verificar se é admin
        $usuario = $_SESSION['usuario'] ?? null;
        if ($usuario['perfil_id'] != 1) {
            header("Location: " . BASE_URL . "index.php?page=dashboard");
            exit;
        }

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=usuarios");
            exit;
        }

        $model = new Usuario();
        $usuarioEdit = $model->buscarPorId($id);

        if (!$usuarioEdit) {
            header("Location: " . BASE_URL . "index.php?page=usuarios");
            exit;
        }

        $perfis = $model->obterPerfis();

        $this->view('usuarios/edit', compact('usuarioEdit', 'perfis'));
    }

    public function update()
    {
        $this->proteger();

        // Verificar se é admin
        $usuario = $_SESSION['usuario'] ?? null;
        if ($usuario['perfil_id'] != 1) {
            header("Location: " . BASE_URL . "index.php?page=dashboard");
            exit;
        }

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=usuarios");
            exit;
        }

        // Validação
        $required = ['nome', 'email', 'perfil_id'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $model = new Usuario();
                $usuarioEdit = $model->buscarPorId($id);
                $perfis = $model->obterPerfis();
                $erro = 'Todos os campos são obrigatórios';
                $this->view('usuarios/edit', compact('usuarioEdit', 'perfis', 'erro'));
                return;
            }
        }

        // Validar email
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $model = new Usuario();
            $usuarioEdit = $model->buscarPorId($id);
            $perfis = $model->obterPerfis();
            $erro = 'Email inválido';
            $this->view('usuarios/edit', compact('usuarioEdit', 'perfis', 'erro'));
            return;
        }

        $model = new Usuario();
        try {
            $model->atualizar($id, $_POST);

            header("Location: " . BASE_URL . "index.php?page=usuarios");
            exit;
        } catch (Exception $e) {
            $usuarioEdit = $model->buscarPorId($id);
            $perfis = $model->obterPerfis();
            $erro = $e->getMessage();
            $this->view('usuarios/edit', compact('usuarioEdit', 'perfis', 'erro'));
        }
    }

    public function delete()
    {
        $this->proteger();

        // Verificar se é admin
        $usuario = $_SESSION['usuario'] ?? null;
        if ($usuario['perfil_id'] != 1) {
            header("Location: " . BASE_URL . "index.php?page=dashboard");
            exit;
        }

        $id = $_GET['id'] ?? null;
        if ($id) {
            try {
                $model = new Usuario();
                $model->deletar($id);
            } catch (Exception $e) {
                // Ignorar e redirecionar
            }
        }

        header("Location: " . BASE_URL . "index.php?page=usuarios");
        exit;
    }
}
