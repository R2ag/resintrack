<?php
require_once __DIR__ . '/../models/Pesagem.php';
require_once __DIR__ . '/../models/Lote.php';
require_once __DIR__ . '/../core/Controller.php';

class PesagemController extends Controller
{
    public function index()
    {
        $this->proteger();

        $model = new Pesagem();
        $pesagens = $model->listar();

        $this->view('pesagens/index', compact('pesagens'));
    }

    public function create()
    {
        $this->proteger();

        $loteModel = new Lote();
        $lotes = $loteModel->listarAtivos();

        $this->view('pesagens/create', compact('lotes'));
    }

    public function store()
    {
        $this->proteger();

        // validação básica
        $required = ['lote_id','data_pesagem','peso_apurado'];
        foreach ($required as $field) {
            if (empty($_POST[$field]) && $_POST[$field] !== '0') {
                $loteModel = new Lote();
                $lotes = $loteModel->listarAtivos();
                $erro = 'Todos os campos são obrigatórios';
                $this->view('pesagens/create', compact('lotes','erro'));
                return;
            }
        }

        $model = new Pesagem();
        $model->criar($_POST);

        header("Location: " . BASE_URL . "index.php?page=pesagens");
        exit;
    }

    public function edit()
    {
        $this->proteger();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=pesagens");
            exit;
        }

        $model = new Pesagem();
        $pesagem = $model->buscarPorId($id);

        if (!$pesagem) {
            header("Location: " . BASE_URL . "index.php?page=pesagens");
            exit;
        }

        $loteModel = new Lote();
        $lotes = $loteModel->listarAtivos();

        $this->view('pesagens/edit', compact('pesagem', 'lotes'));
    }

    public function update()
    {
        $this->proteger();

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=pesagens");
            exit;
        }

        // validação básica
        $required = ['lote_id','data_pesagem','peso_apurado'];
        foreach ($required as $field) {
            if (empty($_POST[$field]) && $_POST[$field] !== '0') {
                $model = new Pesagem();
                $pesagem = $model->buscarPorId($id);
                $loteModel = new Lote();
                $lotes = $loteModel->listarAtivos();
                $erro = 'Todos os campos são obrigatórios';
                $this->view('pesagens/edit', compact('pesagem', 'lotes', 'erro'));
                return;
            }
        }

        $model = new Pesagem();
        $model->atualizar($id, $_POST);

        header("Location: " . BASE_URL . "index.php?page=pesagens");
        exit;
    }

    public function delete()
    {
        $this->proteger();

        $id = $_GET['id'] ?? null;
        if ($id) {
            $model = new Pesagem();
            $model->deletar($id);
        }

        header("Location: " . BASE_URL . "index.php?page=pesagens");
        exit;
    }
}