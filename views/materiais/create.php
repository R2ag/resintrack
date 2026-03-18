<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Novo Material</h2>
    <a href="<?= BASE_URL ?>index.php?page=materiais" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Novo Material</h5>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?= htmlentities($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?page=materiais_store">
            <div class="row g-3">
                <div class="col-md-12">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?= htmlentities($old['nome'] ?? '') ?>" required>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="<?= BASE_URL ?>index.php?page=materiais" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>