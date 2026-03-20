<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h5 mb-0">Lotes</h2>
  <a href="<?= BASE_URL ?>index.php?page=lote_create" class="btn btn-sm btn-primary">Novo Lote</a>
</div>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-sm table-bordered table-hover align-middle mb-0">
        <thead class="table-light">
          <tr>
            <th>Lote</th>
            <th>Insumo</th>
            <th class="text-end">Peso Inicial</th>
            <th class="text-end">Saldo Teórico</th>
            <th class="text-end">Saldo Real</th>
            <th class="text-end">Perda</th>
            <th>Status</th>
            <th class="text-center" style="width: 140px">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($lotes as $l): ?>
          <tr>
            <td><?= htmlentities($l['lote']) ?></td>
            <td>
              <a href="<?= BASE_URL ?>index.php?page=lote_entradas&id=<?= $l['id'] ?>" class="text-decoration-none">
                <?= htmlentities($l['insumo_nome']) ?>
              </a>
            </td>
            <td class="text-end"><?= number_format($l['peso_inicial'],3,',','.') ?></td>
            <td class="text-end"><?= number_format($l['saldo_teorico'],3,',','.') ?></td>
            <td class="text-end"><?= number_format($l['saldo_real'],3,',','.') ?></td>
            <td class="text-end">
              <span class="<?= $l['perda'] > 0 ? 'text-danger' : 'text-success' ?>"><?= number_format($l['perda'],3,',','.') ?></span>
            </td>
            <td>
              <?= $l['data_fim'] ? '<span class="badge bg-secondary">Finalizado</span>' : '<span class="badge bg-success">Ativo</span>' ?>
            </td>
            <td class="text-center">
              <a href="<?= BASE_URL ?>index.php?page=lote_edit&id=<?= $l['id'] ?>" class="btn btn-xs btn-warning" title="Editar">Editar</a>
              <a href="<?= BASE_URL ?>index.php?page=lote_delete&id=<?= $l['id'] ?>" class="btn btn-xs btn-danger" title="Deletar" onclick="return confirm('Tem certeza que deseja deletar este lote? Todas as pesagens e execuções serão removidos.')">Deletar</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (empty($lotes)): ?>
        <div class="alert alert-secondary mt-2">Nenhum lote cadastrado.</div>
      <?php endif; ?>
    </div>
  </div>
</div>
