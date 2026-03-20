<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Editar Material</h2>
    <a href="<?= BASE_URL ?>index.php?page=materiais" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Editar Material</h5>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?= htmlentities($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?page=materiais_update">
            <input type="hidden" name="id" value="<?= htmlentities($material['id']) ?>">

            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?= htmlentities($material['nome']) ?>" required>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="<?= BASE_URL ?>index.php?page=materiais" class="btn btn-outline-secondary">Cancelar</a>
                <form method="POST" action="<?= BASE_URL ?>index.php?page=materiais_delete" style="display: inline;">
                    <input type="hidden" name="id" value="<?= htmlentities($material['id']) ?>">
                    <button type="submit" class="btn btn-outline-danger ms-auto" onclick="return confirm('Tem certeza que deseja deletar este material?')">Deletar</button>
                </form>
            </div>
        </form>
    </div>
</div>