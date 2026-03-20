<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Nova Pesagem</h2>
    <a href="<?= BASE_URL ?>index.php?page=pesagens" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Nova Pesagem</h5>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?= htmlentities($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?page=pesagens_store">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Lote</label>
                    <select name="lote_id" class="form-select" required>
                        <?php foreach ($lotes as $l): ?>
                            <option value="<?= $l['id'] ?>" <?= isset($old['lote_id']) && $old['lote_id'] == $l['id'] ? 'selected' : '' ?>><?= htmlentities($l['lote']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Data da Pesagem</label>
                    <input type="date" name="data_pesagem" class="form-control" value="<?= htmlentities($old['data_pesagem'] ?? '') ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Peso Apurado (kg)</label>
                    <input type="number" step="0.01" name="peso_apurado" class="form-control" value="<?= htmlentities($old['peso_apurado'] ?? '') ?>" required>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
                <a href="<?= BASE_URL ?>index.php?page=pesagens" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>