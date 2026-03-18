<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Controller.php';

$pagina = $_GET['page'] ?? 'dashboard';

switch ($pagina) {
    case 'login':
        require_once "../app/controllers/AuthController.php";
        $controller = new AuthController();
        $controller->login();
        break;

    // other cases come later; the default handling is done at end of switch

    case 'insumos':
        require_once "../app/controllers/InsumoController.php";
        $controller = new InsumoController();
        $controller->index();
        break;

    case 'insumo_create':
        require_once "../app/controllers/InsumoController.php";
        $controller = new InsumoController();
        $controller->create();
        break;

    case 'insumo_edit':
        require_once "../app/controllers/InsumoController.php";
        $controller = new InsumoController();
        $controller->edit();
        break;

    case 'insumo_delete':
        require_once "../app/controllers/InsumoController.php";
        $controller = new InsumoController();
        $controller->delete();
        break;

    case 'lotes':
        require_once "../app/controllers/LoteController.php";
        $controller = new LoteController();
        $controller->index();
        break;

    case 'lote_create':
        require_once "../app/controllers/LoteController.php";
        $controller = new LoteController();
        $controller->create();
        break;

    case 'lote_edit':
        require_once "../app/controllers/LoteController.php";
        $controller = new LoteController();
        $controller->edit();
        break;

    case 'lote_delete':
        require_once "../app/controllers/LoteController.php";
        $controller = new LoteController();
        $controller->delete();
        break;

    case 'lote_entradas':
        require_once "../app/controllers/LoteController.php";
        $controller = new LoteController();
        $controller->adicionarEntrada();
        break;

    case 'lote_adicionar_entrada':
        require_once "../app/controllers/LoteController.php";
        $controller = new LoteController();
        $controller->adicionarEntrada();
        break;

    case 'pesagens':
        require_once '../app/controllers/PesagemController.php';
        (new PesagemController())->index();
        break;

    case 'pesagens_create':
        require_once '../app/controllers/PesagemController.php';
        (new PesagemController())->create();
        break;

    case 'pesagens_store':
        require_once '../app/controllers/PesagemController.php';
        (new PesagemController())->store();
        break;

    case 'pesagens_edit':
        require_once '../app/controllers/PesagemController.php';
        (new PesagemController())->edit();
        break;

    case 'pesagens_update':
        require_once '../app/controllers/PesagemController.php';
        (new PesagemController())->update();
        break;

    case 'pesagens_delete':
        require_once '../app/controllers/PesagemController.php';
        (new PesagemController())->delete();
        break;

    case 'processos':
        require_once '../app/controllers/ProcessoController.php';
        (new ProcessoController())->index();
        break;

    case 'processos_create':
        require_once '../app/controllers/ProcessoController.php';
        (new ProcessoController())->create();
        break;

    case 'processos_store':
        require_once '../app/controllers/ProcessoController.php';
        (new ProcessoController())->store();
        break;

    case 'processos_edit':
        require_once '../app/controllers/ProcessoController.php';
        (new ProcessoController())->edit();
        break;

    case 'processos_update':
        require_once '../app/controllers/ProcessoController.php';
        (new ProcessoController())->update();
        break;

    case 'processos_delete':
        require_once '../app/controllers/ProcessoController.php';
        (new ProcessoController())->delete();
        break;

    case 'materiais':
        require_once '../app/controllers/MaterialController.php';
        (new MaterialController())->index();
        break;

    case 'materiais_create':
        require_once '../app/controllers/MaterialController.php';
        (new MaterialController())->create();
        break;

    case 'materiais_store':
        require_once '../app/controllers/MaterialController.php';
        (new MaterialController())->store();
        break;

    case 'materiais_edit':
        require_once '../app/controllers/MaterialController.php';
        (new MaterialController())->edit();
        break;

    case 'materiais_update':
        require_once '../app/controllers/MaterialController.php';
        (new MaterialController())->update();
        break;

    case 'materiais_delete':
        require_once '../app/controllers/MaterialController.php';
        (new MaterialController())->delete();
        break;

    case 'blocos':
        require_once '../app/controllers/BlocoController.php';
        (new BlocoController())->index();
        break;

    case 'blocos_create':
        require_once '../app/controllers/BlocoController.php';
        (new BlocoController())->create();
        break;

    case 'blocos_store':
        require_once '../app/controllers/BlocoController.php';
        (new BlocoController())->store();
        break;

    case 'blocos_edit':
        require_once '../app/controllers/BlocoController.php';
        (new BlocoController())->edit();
        break;

    case 'blocos_update':
        require_once '../app/controllers/BlocoController.php';
        (new BlocoController())->update();
        break;

    case 'blocos_delete':
        require_once '../app/controllers/BlocoController.php';
        (new BlocoController())->delete();
        break;

    case 'execucoes_processos':
        require_once '../app/controllers/ExecucaoProcessoController.php';
        (new ExecucaoProcessoController())->index();
        break;

    case 'execucoes_processos_create':
        require_once '../app/controllers/ExecucaoProcessoController.php';
        (new ExecucaoProcessoController())->create();
        break;

    case 'execucoes_processos_store':
        require_once '../app/controllers/ExecucaoProcessoController.php';
        (new ExecucaoProcessoController())->store();
        break;

    case 'execucoes_processos_edit':
        require_once '../app/controllers/ExecucaoProcessoController.php';
        (new ExecucaoProcessoController())->edit();
        break;

    case 'execucoes_processos_update':
        require_once '../app/controllers/ExecucaoProcessoController.php';
        (new ExecucaoProcessoController())->update();
        break;

    case 'execucoes_processos_delete':
        require_once '../app/controllers/ExecucaoProcessoController.php';
        (new ExecucaoProcessoController())->delete();
        break;

    case 'usuarios':
        require_once '../app/controllers/UsuarioController.php';
        (new UsuarioController())->index();
        break;

    case 'usuarios_create':
        require_once '../app/controllers/UsuarioController.php';
        (new UsuarioController())->create();
        break;

    case 'usuarios_store':
        require_once '../app/controllers/UsuarioController.php';
        (new UsuarioController())->store();
        break;

    case 'usuarios_edit':
        require_once '../app/controllers/UsuarioController.php';
        (new UsuarioController())->edit();
        break;

    case 'usuarios_update':
        require_once '../app/controllers/UsuarioController.php';
        (new UsuarioController())->update();
        break;

    case 'usuarios_delete':
        require_once '../app/controllers/UsuarioController.php';
        (new UsuarioController())->delete();
        break;

    case 'dashboard':
        require_once '../app/controllers/DashboardController.php';
        (new DashboardController())->index();
        break;

    default:
        // fallback to dashboard if page is invalid
        require_once '../app/controllers/DashboardController.php';
        (new DashboardController())->index();
        break;
}
