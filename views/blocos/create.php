<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Novo Bloco</h2>
    <a href="<?= BASE_URL ?>index.php?page=blocos" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Novo Bloco</h5>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?= htmlentities($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?page=blocos_store">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Código</label>
                    <input type="text" name="codigo" class="form-control" value="<?= htmlentities($old['codigo'] ?? '') ?>" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Material</label>
                    <select name="material_id" class="form-select" required>
                        <option value="">Selecione um material</option>
                        <?php foreach ($materiais as $m): ?>
                            <option value="<?= $m['id'] ?>" <?= isset($old['material_id']) && $old['material_id'] == $m['id'] ? 'selected' : '' ?>><?= htmlentities($m['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Número de Chapas</label>
                    <input type="number" name="numero_chapas" class="form-control" value="<?= htmlentities($old['numero_chapas'] ?? '') ?>" required>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="<?= BASE_URL ?>index.php?page=blocos" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>