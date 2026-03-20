<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Lote.php';
require_once __DIR__ . '/../models/Insumo.php';

class LoteController extends Controller {

    // reuse Controller::proteger() rather than custom auth logic

    public function index() {
        $this->proteger();

        $model = new Lote();
        $lotes = $model->listar();

        $this->view("lotes/index", compact("lotes"));
    }

    public function create() {
        $this->proteger();

        $insumoModel = new Insumo();
        $insumos = $insumoModel->listar();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // validações simples
            $dados = [
                'lote' => trim($_POST['lote'] ?? ''),
                'insumo_id' => $_POST['insumo_id'] ?? null,
                'peso_inicial' => $_POST['peso_inicial'] ?? null,
                'tara' => $_POST['tara'] ?? null,
                'data_entrada' => $_POST['data_entrada'] ?? ''
            ];

            if ($dados['lote'] === '' || !$dados['insumo_id'] || $dados['peso_inicial'] === null || $dados['tara'] === null || $dados['data_entrada'] === '') {
                $erro = 'Todos os campos obrigatórios devem ser preenchidos';
                // inclui valores antigos para repopular o formulário
                $old = $dados;
                $this->view('lotes/create', compact('insumos','erro','old'));
                return;
            }

            $model = new Lote();

            $model->criar([
                ':lote' => $dados['lote'],
                ':insumo_id' => $dados['insumo_id'],
                ':peso_inicial' => $dados['peso_inicial'],
                ':tara' => $dados['tara'],
                ':data_entrada' => $dados['data_entrada']
            ]);

            header("Location: " . BASE_URL . "index.php?page=lotes");
            exit;
        }

        $this->view("lotes/create", compact("insumos"));
    }

    public function edit() {
        $this->proteger();

        $model = new Lote();
        $insumoModel = new Insumo();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $dados = [
                'id' => $_POST['id'],
                'lote' => trim($_POST['lote'] ?? ''),
                'insumo_id' => $_POST['insumo_id'] ?? null,
                'peso_inicial' => $_POST['peso_inicial'] ?? null,
                'tara' => $_POST['tara'] ?? null,
                'data_entrada' => $_POST['data_entrada'] ?? '',
                'data_fim' => $_POST['data_fim'] ?: null
            ];

            if ($dados['lote'] === '' || !$dados['insumo_id'] || $dados['peso_inicial'] === null || $dados['tara'] === null || $dados['data_entrada'] === '') {
                $erro = 'Todos os campos obrigatórios devem ser preenchidos';
                $lote = $dados; // repor valores
                $insumos = $insumoModel->listar();
                $this->view('lotes/edit', compact('lote','insumos','erro'));
                return;
            }

            $model->atualizar([
                ':id' => $dados['id'],
                ':lote' => $dados['lote'],
                ':insumo_id' => $dados['insumo_id'],
                ':peso_inicial' => $dados['peso_inicial'],
                ':tara' => $dados['tara'],
                ':data_entrada' => $dados['data_entrada'],
                ':data_fim' => $dados['data_fim'] ?: null
            ]);

            header("Location: " . BASE_URL . "index.php?page=lotes");
            exit;
        }

        $lote = $model->buscar($_GET['id']);
        $insumos = $insumoModel->listar();

        $this->view("lotes/edit", compact("lote","insumos"));
    }

    public function adicionarEntrada() {
        $this->proteger();

        $model = new Lote();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $lote_id = $_POST['lote_id'] ?? null;
            $quantidade = $_POST['quantidade'] ?? null;
            $data_entrada = $_POST['data_entrada'] ?? null;

            if (!$lote_id || !$quantidade || !$data_entrada) {
                $erro = 'Todos os campos são obrigatórios';
                $lote = $model->buscar($lote_id);
                $entradas = $model->getEntradas($lote_id);
                $this->view('lotes/entradas', compact('lote','entradas','erro'));
                return;
            }

            $model->adicionarEntrada($lote_id, $quantidade, $data_entrada);

            header("Location: " . BASE_URL . "index.php?page=lote_entradas&id=" . $lote_id);
            exit;
        }

        $lote_id = $_GET['id'] ?? null;
        if (!$lote_id) {
            header("Location: " . BASE_URL . "index.php?page=lotes");
            exit;
        }

        $lote = $model->buscar($lote_id);
        $entradas = $model->getEntradas($lote_id);

        $this->view("lotes/entradas", compact("lote","entradas"));
    }

    public function delete() {
        $this->proteger();

        $id = $_GET['id'] ?? null;
        if ($id) {
            $model = new Lote();
            $model->deletar($id);
        }

        header("Location: " . BASE_URL . "index.php?page=lotes");
        exit;
    }
}