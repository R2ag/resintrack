<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 class="h5 mb-0">Insumos</h2>
  <a href="<?= BASE_URL ?>index.php?page=insumo_create" class="btn btn-sm btn-primary">Novo Insumo</a>
</div>

<?php if (isset($_GET['error']) && $_GET['error'] === 'delete'): ?>
    <div class="alert alert-warning">Não é possível excluir um insumo que possui lotes vinculados.</div>
<?php endif; ?>

<div class="card">
  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-sm table-bordered align-middle mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Descrição</th>
            <th width="150">Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($insumos as $i): ?>
          <tr>
            <td><?= $i['id'] ?></td>
            <td><?= htmlentities($i['descricao']) ?></td>
            <td>
              <a href="<?= BASE_URL ?>index.php?page=insumo_edit&id=<?= $i['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
              <a href="<?= BASE_URL ?>index.php?page=insumo_delete&id=<?= $i['id'] ?>" 
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('Deseja excluir?')">Excluir</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php if (empty($insumos)): ?>
        <div class="alert alert-secondary mt-2">Nenhum insumo cadastrado.</div>
      <?php endif; ?>
    </div>
  </div>
</div>
