<?php
require_once __DIR__ . '/../models/ExecucaoProcesso.php';
require_once __DIR__ . '/../models/Processo.php';
require_once __DIR__ . '/../models/Bloco.php';
require_once __DIR__ . '/../models/Lote.php';
require_once __DIR__ . '/../core/Controller.php';

class ExecucaoProcessoController extends Controller
{
    public function index()
    {
        $this->proteger();

        $model = new ExecucaoProcesso();
        $execucoes = $model->listar();

        $this->view('execucoes_processos/index', compact('execucoes'));
    }

    public function create()
    {
        $this->proteger();

        $processoModel = new Processo();
        $processos = $processoModel->listar();

        $blocoModel = new Bloco();
        $blocos = $blocoModel->listar();

        $loteModel = new Lote();
        $lotes = $loteModel->listarAtivos();

        $this->view('execucoes_processos/create', compact('processos', 'blocos', 'lotes'));
    }

    public function store()
    {
        $this->proteger();

        // Validação básica dos campos
        $required = ['processo_id', 'bloco_id', 'data_execucao', 'qtd_chapas'];
        foreach ($required as $field) {
            if (empty($_POST[$field]) && $_POST[$field] !== '0') {
                // Volta ao formulário com mensagem de erro
                $processoModel = new Processo();
                $processos = $processoModel->listar();
                $blocoModel = new Bloco();
                $blocos = $blocoModel->listar();
                $loteModel = new Lote();
                $lotes = $loteModel->listarAtivos();
                $erro = 'Todos os campos são obrigatórios';
                $old = $_POST;
                $this->view('execucoes_processos/create', compact('processos', 'blocos', 'lotes', 'erro', 'old'));
                return;
            }
        }

        // Processar insumos
        $insumos = [];
        if (isset($_POST['lote_id']) && is_array($_POST['lote_id'])) {
            foreach ($_POST['lote_id'] as $key => $lote_id) {
                if (!empty($lote_id) && !empty($_POST['quantidade_por_chapa'][$key])) {
                    $insumos[] = [
                        'lote_id' => $lote_id,
                        'quantidade_por_chapa' => $_POST['quantidade_por_chapa'][$key]
                    ];
                }
            }
        }

        if (empty($insumos)) {
            $processoModel = new Processo();
            $processos = $processoModel->listar();
            $loteModel = new Lote();
            $lotes = $loteModel->listarAtivos();
            $erro = 'Pelo menos um insumo deve ser informado';
            $old = $_POST;
            $this->view('execucoes_processos/create', compact('processos', 'lotes', 'erro', 'old'));
            return;
        }

        $_POST['insumos'] = $insumos;

        $model = new ExecucaoProcesso();
        $model->criar($_POST);

        header("Location: " . BASE_URL . "index.php?page=execucoes_processos");
        exit;
    }

    public function edit()
    {
        $this->proteger();

        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=execucoes_processos");
            exit;
        }

        $model = new ExecucaoProcesso();
        $execucao = $model->buscarPorId($id);

        if (!$execucao) {
            header("Location: " . BASE_URL . "index.php?page=execucoes_processos");
            exit;
        }

        $processoModel = new Processo();
        $processos = $processoModel->listar();

        $blocoModel = new Bloco();
        $blocos = $blocoModel->listar();

        $loteModel = new Lote();
        $lotes = $loteModel->listarAtivos();

        $this->view('execucoes_processos/edit', compact('execucao', 'processos', 'blocos', 'lotes'));
    }

    public function update()
    {
        $this->proteger();

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=execucoes_processos");
            exit;
        }

        // Validação básica dos campos
        $required = ['processo_id', 'bloco_id', 'data_execucao', 'qtd_chapas'];
        foreach ($required as $field) {
            if (empty($_POST[$field]) && $_POST[$field] !== '0') {
                $model = new ExecucaoProcesso();
                $execucao = $model->buscarPorId($id);
                $processoModel = new Processo();
                $processos = $processoModel->listar();
                $blocoModel = new Bloco();
                $blocos = $blocoModel->listar();
                $loteModel = new Lote();
                $lotes = $loteModel->listarAtivos();
                $erro = 'Todos os campos são obrigatórios';
                $this->view('execucoes_processos/edit', compact('execucao', 'processos', 'blocos', 'lotes', 'erro'));
                return;
            }
        }

        // Processar insumos
        $insumos = [];
        if (isset($_POST['lote_id']) && is_array($_POST['lote_id'])) {
            foreach ($_POST['lote_id'] as $key => $lote_id) {
                if (!empty($lote_id) && !empty($_POST['quantidade_por_chapa'][$key])) {
                    $insumos[] = [
                        'lote_id' => $lote_id,
                        'quantidade_por_chapa' => $_POST['quantidade_por_chapa'][$key]
                    ];
                }
            }
        }

        if (empty($insumos)) {
            $model = new ExecucaoProcesso();
            $execucao = $model->buscarPorId($id);
            $processoModel = new Processo();
            $processos = $processoModel->listar();
            $loteModel = new Lote();
            $lotes = $loteModel->listarAtivos();
            $erro = 'Pelo menos um insumo deve ser informado';
            $this->view('execucoes_processos/edit', compact('execucao', 'processos', 'lotes', 'erro'));
            return;
        }

        $_POST['insumos'] = $insumos;

        $model = new ExecucaoProcesso();
        $model->atualizar($id, $_POST);

        header("Location: " . BASE_URL . "index.php?page=execucoes_processos");
        exit;
    }

    public function delete()
    {
        $this->proteger();

        $id = $_POST['id'] ?? null;
        if (!$id) {
            header("Location: " . BASE_URL . "index.php?page=execucoes_processos");
            exit;
        }

        $model = new ExecucaoProcesso();
        $model->deletar($id);

        header("Location: " . BASE_URL . "index.php?page=execucoes_processos");
        exit;
    }
}
?>