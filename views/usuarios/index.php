<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Usuários</h2>
    <a href="<?= BASE_URL ?>index.php?page=usuarios_create" class="btn btn-sm btn-primary">Novo Usuário</a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($usuarios)): ?>
            <div class="alert alert-secondary">Nenhum usuário cadastrado.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 140px">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><strong><?= htmlentities($u['nome']) ?></strong></td>
                                <td><?= htmlentities($u['email']) ?></td>
                                <td>
                                    <span class="badge bg-info"><?= htmlentities($u['perfil_nome']) ?></span>
                                </td>
                                <td class="text-center">
                                    <?= $u['ativo'] ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-danger">Inativo</span>' ?>
                                </td>
                                <td class="text-center">
                                    <a href="<?= BASE_URL ?>index.php?page=usuarios_edit&id=<?= htmlentities($u['id']) ?>" class="btn btn-xs btn-warning" title="Editar">Editar</a>
                                    <?php if ($u['perfil_id'] != 1): ?>
                                        <a href="<?= BASE_URL ?>index.php?page=usuarios_delete&id=<?= htmlentities($u['id']) ?>" class="btn btn-xs btn-danger" title="Deletar" onclick="return confirm('Tem certeza que deseja deletar este usuário?')">Deletar</a>
                                    <?php else: ?>
                                        <span class="text-muted small">Admin</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
