<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h5 mb-0">Saídas do Lote: <?= htmlentities($lote['lote']) ?></h2>
  <a href="<?= BASE_URL ?>index.php?page=lotes" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Histórico de Saídas</h5>

        <div class="table-responsive">
          <table class="table table-sm table-bordered table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Data</th>
                <th>Motivo</th>
                <th class="text-end">Quantidade (kg)</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($saidas as $s): ?>
              <tr>
                <td><?= date('d/m/Y', strtotime($s['data_saida'])) ?></td>
                <td><?= htmlentities($s['motivo'] ?? '-') ?></td>
                <td class="text-end"><?= number_format($s['quantidade'], 3, ',', '.') ?></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot class="table-light">
              <tr>
                <th colspan="2">Total</th>
                <th class="text-end"><?= number_format(array_sum(array_column($saidas, 'quantidade')), 3, ',', '.') ?> kg</th>
              </tr>
            </tfoot>
          </table>
          <?php if (empty($saidas)): ?>
            <div class="alert alert-secondary mt-2">Nenhuma saída registrada.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Adicionar Saída</h5>

        <?php if (isset($erro)): ?>
          <div class="alert alert-danger"><?= htmlentities($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?page=lote_adicionar_saida">
          <input type="hidden" name="lote_id" value="<?= $lote['id'] ?>">

          <div class="mb-3">
            <label class="form-label">Quantidade (kg)</label>
            <input type="number" step="0.001" name="quantidade" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Data de saída</label>
            <input type="date" name="data_saida" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Motivo</label>
            <input type="text" name="motivo" class="form-control" maxlength="255" placeholder="Ex: uso em processo, descarte">
          </div>

          <button class="btn btn-primary w-100">Adicionar Saída</button>
        </form>
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-body">
        <h6 class="card-title">Informações do Lote</h6>
        <dl class="row mb-0">
          <dt class="col-sm-5">Insumo:</dt>
          <dd class="col-sm-7"><?= htmlentities($lote['insumo_nome'] ?? 'N/A') ?></dd>

          <dt class="col-sm-5">Peso Inicial:</dt>
          <dd class="col-sm-7"><?= number_format($lote['peso_inicial'], 3, ',', '.') ?> kg</dd>

          <dt class="col-sm-5">Tara:</dt>
          <dd class="col-sm-7"><?= number_format($lote['tara'], 3, ',', '.') ?> kg</dd>

          <dt class="col-sm-5">Saldo Atual:</dt>
          <dd class="col-sm-7"><?= number_format($lote['saldo_atual'], 3, ',', '.') ?> kg</dd>
        </dl>
      </div>
    </div>
  </div>
</div>