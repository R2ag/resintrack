<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Processos</h2>
    <a href="<?= BASE_URL ?>index.php?page=processos_create" class="btn btn-sm btn-primary">Novo Processo</a>
</div>

<div class="card">
  <div class="card-body">
    <?php if (empty($processos)): ?>
      <div class="alert alert-secondary">Nenhum processo cadastrado.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-sm table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th class="text-center" style="width: 120px">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($processos as $p): ?>
            <tr>
                <td><?= htmlentities($p['codigo']) ?></td>
                <td><?= htmlentities($p['nome']) ?></td>
                <td><?= htmlentities($p['descricao'] ?? '') ?></td>
                <td class="text-center">
                    <a href="<?= BASE_URL ?>index.php?page=processos_edit&id=<?= htmlentities($p['id']) ?>" class="btn btn-xs btn-warning" title="Editar">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <a href="<?= BASE_URL ?>index.php?page=processos_delete&id=<?= htmlentities($p['id']) ?>" class="btn btn-xs btn-danger" title="Deletar" onclick="return confirm('Tem certeza?')">
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