<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Pesagens</h2>
    <a href="<?= BASE_URL ?>index.php?page=pesagens_create" class="btn btn-sm btn-primary">Nova Pesagem</a>
</div>

<div class="card">
  <div class="card-body">
    <?php if (empty($pesagens)): ?>
      <div class="alert alert-secondary">Nenhuma pesagem registrada.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-sm table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
                <th>Lote</th>
                <th>Data</th>
                <th class="text-end">Peso Apurado</th>
                <th class="text-center" style="width: 120px">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pesagens as $p): ?>
                <tr>
                    <td><?= htmlentities($p['lote']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($p['data_pesagem'])) ?></td>
                    <td class="text-end"><?= number_format($p['peso_apurado'], 2, ',', '.') ?> kg</td>
                    <td class="text-center">
                        <a href="<?= BASE_URL ?>index.php?page=pesagens_edit&id=<?= htmlentities($p['id']) ?>" class="btn btn-xs btn-warning" title="Editar">
                            <i class="bi bi-pencil"></i> Editar
                        </a>
                        <a href="<?= BASE_URL ?>index.php?page=pesagens_delete&id=<?= htmlentities($p['id']) ?>" class="btn btn-xs btn-danger" title="Deletar" onclick="return confirm('Tem certeza?')">
                            <i class="bi bi-trash"></i> Del
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>
