<!DOCTYPE html>
<html lang="pt-br">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Controle de Resinas</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="<?= BASE_URL ?>assets/css/custom.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-light">

<?php 
$user = $_SESSION['usuario'] ?? null;
$current_page = $_GET['page'] ?? '';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="<?= BASE_URL ?>index.php?page=dashboard">ResinTrack</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <?php if ($user): ?>
                    <li class="nav-item"><a class="nav-link<?= $current_page==='dashboard' ? ' active' : '' ?>" href="<?= BASE_URL ?>index.php?page=dashboard">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link<?= $current_page==='insumos' ? ' active' : '' ?>" href="<?= BASE_URL ?>index.php?page=insumos">Insumos</a></li>
                    <li class="nav-item"><a class="nav-link<?= $current_page==='lotes' ? ' active' : '' ?>" href="<?= BASE_URL ?>index.php?page=lotes">Lotes</a></li>
                    <li class="nav-item"><a class="nav-link<?= $current_page==='pesagens' ? ' active' : '' ?>" href="<?= BASE_URL ?>index.php?page=pesagens">Pesagens</a></li>
                    <li class="nav-item"><a class="nav-link<?= $current_page==='execucoes_processos' ? ' active' : '' ?>" href="<?= BASE_URL ?>index.php?page=execucoes_processos">Execuções</a></li>
                    <li class="nav-item"><a class="nav-link<?= $current_page==='processos' ? ' active' : '' ?>" href="<?= BASE_URL ?>index.php?page=processos">Processos</a></li>
                    <li class="nav-item"><a class="nav-link<?= $current_page==='materiais' ? ' active' : '' ?>" href="<?= BASE_URL ?>index.php?page=materiais">Materiais</a></li>
                    <li class="nav-item"><a class="nav-link<?= $current_page==='blocos' ? ' active' : '' ?>" href="<?= BASE_URL ?>index.php?page=blocos">Blocos</a></li>
                    <?php if (isset($user['perfil_id']) && $user['perfil_id'] == 1): ?>
                        <li class="nav-item"><a class="nav-link<?= $current_page==='usuarios' ? ' active' : '' ?>" href="<?= BASE_URL ?>index.php?page=usuarios">Usuários</a></li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>logout.php" class="nav-link">Sair</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>index.php?page=login">Entrar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container my-4">