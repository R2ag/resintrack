<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h5 mb-0">Editar Lote</h2>
  <a href="<?= BASE_URL ?>index.php?page=lotes" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="card">
  <div class="card-body">
    <h5 class="card-title">Editar Lote</h5>

    <?php if (isset($erro)): ?>
      <div class="alert alert-danger"><?= htmlentities($erro) ?></div>
    <?php endif; ?>

    <form method="POST" action="<?= BASE_URL ?>index.php?page=lote_edit">
      <input type="hidden" name="id" value="<?= $lote['id'] ?>">

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Lote</label>
          <input type="text" name="lote" class="form-control" value="<?= htmlentities($lote['lote']) ?>" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Insumo</label>
          <select name="insumo_id" class="form-select" required>
            <?php foreach ($insumos as $i): ?>
              <option value="<?= $i['id'] ?>" <?= $i['id'] == $lote['insumo_id'] ? 'selected' : '' ?>><?= htmlentities($i['descricao']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Peso inicial (kg)</label>
          <input type="number" step="0.001" name="peso_inicial" class="form-control" value="<?= htmlentities($lote['peso_inicial']) ?>" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Tara (kg)</label>
          <input type="number" step="0.001" name="tara" class="form-control" value="<?= htmlentities($lote['tara']) ?>" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Data de entrada</label>
          <input type="date" name="data_entrada" class="form-control" value="<?= htmlentities($lote['data_entrada']) ?>" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Data fim (opcional)</label>
          <input type="date" name="data_fim" class="form-control" value="<?= htmlentities($lote['data_fim']) ?>">
        </div>
      </div>

      <div class="mt-3 d-flex gap-2">
        <button class="btn btn-primary">Salvar</button>
        <a href="<?= BASE_URL ?>index.php?page=lotes" class="btn btn-outline-secondary">Cancelar</a>
        <a href="<?= BASE_URL ?>index.php?page=lote_delete&id=<?= htmlentities($lote['id']) ?>" class="btn btn-outline-danger ms-auto" onclick="return confirm('Tem certeza que deseja deletar este lote? Todas as pesagens e execuções serão removidos.')">Deletar</a>
      </div>
    </form>
  </div>
</div>
