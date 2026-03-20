<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Editar Insumo</h2>
    <a href="<?= BASE_URL ?>index.php?page=insumos" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Editar Insumo</h5>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?= htmlentities($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?page=insumo_edit">
            <input type="hidden" name="id" value="<?= $insumo['id'] ?>">

            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <input type="text" name="descricao" value="<?= htmlentities($insumo['descricao']) ?>" class="form-control" required>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-success">Atualizar</button>
                <a href="index.php?page=insumos" class="btn btn-outline-secondary">Voltar</a>
            </div>
        </form>
    </div>
</div>