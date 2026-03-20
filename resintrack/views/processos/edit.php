<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Editar Processo</h2>
    <a href="<?= BASE_URL ?>index.php?page=processos" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Editar Processo</h5>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?= htmlentities($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?page=processos_update">
            <input type="hidden" name="id" value="<?= htmlentities($processo['id']) ?>">
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Código</label>
                    <input type="text" name="codigo" class="form-control" value="<?= htmlentities($processo['codigo']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?= htmlentities($processo['nome']) ?>" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Descrição</label>
                    <textarea name="descricao" class="form-control" rows="3"><?= htmlentities($processo['descricao'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="<?= BASE_URL ?>index.php?page=processos" class="btn btn-outline-secondary">Cancelar</a>
                <a href="<?= BASE_URL ?>index.php?page=processos_delete&id=<?= htmlentities($processo['id']) ?>" class="btn btn-outline-danger ms-auto" onclick="return confirm('Tem certeza que deseja deletar este processo?')">Deletar</a>
            </div>
        </form>
    </div>
</div>