<?php
require_once __DIR__ . '/../models/Material.php';
require_once __DIR__ . '/../core/Controller.php';

class MaterialController extends Controller
{
    public function index()
    {
        $this->proteger();

        $model = new Material();
        $materiais = $model->listar();

        $this->view('materiais/index', compact('materiais'));
    }

    public function create()
    {
        $this->proteger();

        $this->view('materiais/create');
    }

    public function store()
    {
        $this->proteger();

        $required = ['nome'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $erro = 'Nome é obrigatório';
                $old = $_POST;
                $this->view('materiais/create', compact('erro', 'old'));
                return;
            }
        }

        $model = new Material();
        $model->criar($_POST);

        header("Location: " . BASE_URL . "index.php?page=materiais");
        exit;
    }

    public function edit()
    {
        $this->proteger();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=materiais");
            exit;
        }

        $model = new Material();
        $material = $model->buscarPorId($id);

        if (!$material) {
            header("Location: " . BASE_URL . "index.php?page=materiais");
            exit;
        }

        $this->view('materiais/edit', compact('material'));
    }

    public function update()
    {
        $this->proteger();

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=materiais");
            exit;
        }

        $required = ['nome'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $model = new Material();
                $material = $model->buscarPorId($id);
                $erro = 'Nome é obrigatório';
                $this->view('materiais/edit', compact('material', 'erro'));
                return;
            }
        }

        $model = new Material();
        $model->atualizar($id, $_POST);

        header("Location: " . BASE_URL . "index.php?page=materiais");
        exit;
    }

    public function delete()
    {
        $this->proteger();

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=materiais");
            exit;
        }

        $model = new Material();
        $model->deletar($id);

        header("Location: " . BASE_URL . "index.php?page=materiais");
        exit;
    }
}
?>