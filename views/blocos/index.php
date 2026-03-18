<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Blocos</h2>
    <a href="<?= BASE_URL ?>index.php?page=blocos_create" class="btn btn-sm btn-primary">Novo Bloco</a>
</div>

<div class="card">
  <div class="card-body">
    <?php if (empty($blocos)): ?>
      <div class="alert alert-secondary">Nenhum bloco cadastrado.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-sm table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
                <th>Código</th>
                <th>Material</th>
                <th class="text-end">Número de Chapas</th>
                <th class="text-center" style="width: 120px">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($blocos as $b): ?>
            <tr>
                <td><?= htmlentities($b['codigo']) ?></td>
                <td><?= htmlentities($b['material_nome']) ?></td>
                <td class="text-end"><?= $b['numero_chapas'] ?></td>
                <td class="text-center">
                    <a href="<?= BASE_URL ?>index.php?page=blocos_edit&id=<?= htmlentities($b['id']) ?>" class="btn btn-xs btn-warning" title="Editar">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <form method="POST" action="<?= BASE_URL ?>index.php?page=blocos_delete" style="display: inline;">
                        <input type="hidden" name="id" value="<?= htmlentities($b['id']) ?>">
                        <button type="submit" class="btn btn-xs btn-danger" title="Deletar" onclick="return confirm('Tem certeza?')">
                            <i class="bi bi-trash"></i> Del
                        </button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endif; ?>
  </div>
</div>