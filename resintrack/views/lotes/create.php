<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h5 mb-0">Novo Lote</h2>
  <a href="<?= BASE_URL ?>index.php?page=lotes" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="card">
  <div class="card-body">
    <h5 class="card-title">Novo Lote</h5>

    <?php if (isset($erro)): ?>
      <div class="alert alert-danger"><?= htmlentities($erro) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>index.php?page=lote_create">
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Lote</label>
          <input type="text" name="lote" class="form-control" value="<?= htmlentities($old['lote'] ?? '') ?>" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Insumo</label>
          <select name="insumo_id" class="form-select" required>
            <option value="">Selecione um insumo</option>
            <?php foreach ($insumos as $i): ?>
              <option value="<?= $i['id'] ?>" <?= isset($old['insumo_id']) && $old['insumo_id'] == $i['id'] ? 'selected' : '' ?>><?= htmlentities($i['descricao']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Peso inicial (kg)</label>
          <input type="number" step="0.001" name="peso_inicial" class="form-control" value="<?= htmlentities($old['peso_inicial'] ?? '') ?>" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Tara (kg)</label>
          <input type="number" step="0.001" name="tara" class="form-control" value="<?= htmlentities($old['tara'] ?? '') ?>" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Data de entrada</label>
          <input type="date" name="data_entrada" class="form-control" value="<?= htmlentities($old['data_entrada'] ?? '') ?>" required>
        </div>
      </div>

      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-primary">Salvar</button>
        <a href="<?= BASE_URL ?>index.php?page=lotes" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </form>
  </div>
</div>
