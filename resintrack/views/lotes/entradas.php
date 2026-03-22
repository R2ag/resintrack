<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h5 mb-0">Entradas do Lote: <?= htmlentities($lote['lote']) ?></h2>
  <div>
    <a href="<?= BASE_URL ?>index.php?page=lote_saidas&id=<?= $lote['id'] ?>" class="btn btn-sm btn-secondary me-2">Ver Saídas</a>
    <a href="<?= BASE_URL ?>index.php?page=lotes" class="btn btn-sm btn-outline-secondary">Voltar</a>
  </div>
</div>

<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Histórico de Entradas</h5>

        <div class="table-responsive">
          <table class="table table-sm table-bordered table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th>Data</th>
                <th class="text-end">Quantidade (kg)</th>
                <th class="text-center">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($entradas as $e): ?>
              <tr>
                <td><?= date('d/m/Y', strtotime($e['data_entrada'])) ?></td>
                <td class="text-end"><?= number_format($e['quantidade'], 3, ',', '.') ?></td>
                <td class="text-center">
                  <button class="btn btn-xs btn-danger" onclick="deletarEntrada(<?= $e['id'] ?>, <?= $lote['id'] ?>)">
                    <i class="bi bi-trash"></i>
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
            <tfoot class="table-light">
              <tr>
                <th>Total</th>
                <th class="text-end"><?= number_format(array_sum(array_column($entradas, 'quantidade')), 3, ',', '.') ?> kg</th>
                <th></th>
              </tr>
            </tfoot>
          </table>
          <?php if (empty($entradas)): ?>
            <div class="alert alert-secondary mt-2">Nenhuma entrada registrada.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Adicionar Entrada</h5>

        <?php if (isset($erro)): ?>
          <div class="alert alert-danger"><?= htmlentities($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?page=lote_adicionar_entrada">
          <input type="hidden" name="lote_id" value="<?= $lote['id'] ?>">

          <div class="mb-3">
            <label class="form-label">Quantidade (kg)</label>
            <input type="number" step="0.001" name="quantidade" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Data de entrada</label>
            <input type="date" name="data_entrada" class="form-control" value="<?= date('Y-m-d') ?>" required>
          </div>

          <button class="btn btn-primary w-100">Adicionar Entrada</button>
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

<script>
function deletarEntrada(entradaId, loteId) {
    if (confirm('Tem certeza que deseja deletar esta entrada?')) {
        // Por enquanto, apenas um alert - implementar endpoint de delete depois
        alert('Funcionalidade de deletar entrada será implementada em breve.');
    }
}
</script>