<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Insumo.php';

class InsumoController extends Controller {

    // as Controller already provides a proteger() helper we can reuse it
    // instead of duplicating the authentication logic.

    public function index() {
        $this->proteger();

        $model = new Insumo();
        $insumos = $model->listar();

        $this->view("insumos/index", compact("insumos"));
    }

    public function create() {
        $this->proteger();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $descricao = trim($_POST['descricao'] ?? '');
            if ($descricao === '') {
                $erro = 'Descrição é obrigatória';
                $this->view('insumos/create', compact('erro'));
                return;
            }

            $model = new Insumo();
            $model->criar($descricao);
            header("Location: " . BASE_URL . "index.php?page=insumos");
            exit;
        }

        $this->view("insumos/create");
    }

    public function edit() {
        $this->proteger();

        $model = new Insumo();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $descricao = trim($_POST['descricao'] ?? '');
            if ($descricao === '') {
                $insumo = ['id' => $_POST['id'], 'descricao' => ''];
                $erro = 'Descrição é obrigatória';
                $this->view('insumos/edit', compact('insumo','erro'));
                return;
            }
            $model->atualizar($_POST['id'], $descricao);
            header("Location: " . BASE_URL . "index.php?page=insumos");
            exit;
        }

        $insumo = $model->buscar($_GET['id']);
        $this->view("insumos/edit", compact("insumo"));
    }

    public function delete() {
        $this->proteger();

        $model = new Insumo();
        $sucesso = $model->excluir($_GET['id']);

        if (!$sucesso) {
            // não foi possível excluir (lotes vinculados)
            header("Location: " . BASE_URL . "index.php?page=insumos&error=delete");
        } else {
            header("Location: " . BASE_URL . "index.php?page=insumos");
        }
        exit;
    }
}