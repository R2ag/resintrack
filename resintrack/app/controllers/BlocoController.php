<?php
require_once __DIR__ . '/../models/Bloco.php';
require_once __DIR__ . '/../models/Material.php';
require_once __DIR__ . '/../core/Controller.php';

class BlocoController extends Controller
{
    public function index()
    {
        $this->proteger();

        $model = new Bloco();
        $blocos = $model->listar();

        $this->view('blocos/index', compact('blocos'));
    }

    public function create()
    {
        $this->proteger();

        $materialModel = new Material();
        $materiais = $materialModel->listar();

        $this->view('blocos/create', compact('materiais'));
    }

    public function store()
    {
        $this->proteger();

        $required = ['codigo', 'material_id', 'numero_chapas'];
        foreach ($required as $field) {
            if (empty($_POST[$field]) && $_POST[$field] !== '0') {
                $materialModel = new Material();
                $materiais = $materialModel->listar();
                $erro = 'Todos os campos são obrigatórios';
                $old = $_POST;
                $this->view('blocos/create', compact('materiais', 'erro', 'old'));
                return;
            }
        }

        $model = new Bloco();
        $model->criar($_POST);

        header("Location: " . BASE_URL . "index.php?page=blocos");
        exit;
    }

    public function edit()
    {
        $this->proteger();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=blocos");
            exit;
        }

        $model = new Bloco();
        $bloco = $model->buscarPorId($id);

        if (!$bloco) {
            header("Location: " . BASE_URL . "index.php?page=blocos");
            exit;
        }

        $materialModel = new Material();
        $materiais = $materialModel->listar();

        $this->view('blocos/edit', compact('bloco', 'materiais'));
    }

    public function update()
    {
        $this->proteger();

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=blocos");
            exit;
        }

        $required = ['codigo', 'material_id', 'numero_chapas'];
        foreach ($required as $field) {
            if (empty($_POST[$field]) && $_POST[$field] !== '0') {
                $model = new Bloco();
                $bloco = $model->buscarPorId($id);
                $materialModel = new Material();
                $materiais = $materialModel->listar();
                $erro = 'Todos os campos são obrigatórios';
                $this->view('blocos/edit', compact('bloco', 'materiais', 'erro'));
                return;
            }
        }

        $model = new Bloco();
        $model->atualizar($id, $_POST);

        header("Location: " . BASE_URL . "index.php?page=blocos");
        exit;
    }

    public function delete()
    {
        $this->proteger();

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=blocos");
            exit;
        }

        $model = new Bloco();
        $model->deletar($id);

        header("Location: " . BASE_URL . "index.php?page=blocos");
        exit;
    }
}
?>