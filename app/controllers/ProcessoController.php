<?php
require_once __DIR__ . '/../models/Processo.php';
require_once __DIR__ . '/../core/Controller.php';

class ProcessoController extends Controller
{
    public function index()
    {
        $this->proteger();

        $model = new Processo();
        $processos = $model->listar();

        $this->view('processos/index', compact('processos'));
    }

    public function create()
    {
        $this->proteger();

        $this->view('processos/create');
    }

    public function store()
    {
        $this->proteger();

        $required = ['codigo', 'nome'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $erro = 'Código e Nome são obrigatórios';
                $old = $_POST;
                $this->view('processos/create', compact('erro', 'old'));
                return;
            }
        }

        $model = new Processo();
        $model->criar($_POST);

        header("Location: " . BASE_URL . "index.php?page=processos");
        exit;
    }

    public function edit()
    {
        $this->proteger();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=processos");
            exit;
        }

        $model = new Processo();
        $processo = $model->buscarPorId($id);

        if (!$processo) {
            header("Location: " . BASE_URL . "index.php?page=processos");
            exit;
        }

        $this->view('processos/edit', compact('processo'));
    }

    public function update()
    {
        $this->proteger();

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=processos");
            exit;
        }

        $required = ['codigo', 'nome'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $model = new Processo();
                $processo = $model->buscarPorId($id);
                $erro = 'Código e Nome são obrigatórios';
                $this->view('processos/edit', compact('processo', 'erro'));
                return;
            }
        }

        $model = new Processo();
        $model->atualizar($id, $_POST);

        header("Location: " . BASE_URL . "index.php?page=processos");
        exit;
    }

    public function delete()
    {
        $this->proteger();

        $id = $_GET['id'] ?? null;
        if ($id) {
            $model = new Processo();
            $model->deletar($id);
        }

        header("Location: " . BASE_URL . "index.php?page=processos");
        exit;
    }
}
?>