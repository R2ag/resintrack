<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Editar Usuário</h2>
    <a href="<?= BASE_URL ?>index.php?page=usuarios" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Editar Usuário</h5>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?= htmlentities($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?page=usuarios_update">
            <input type="hidden" name="id" value="<?= htmlentities($usuarioEdit['id']) ?>">
            
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?= htmlentities($usuarioEdit['nome']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlentities($usuarioEdit['email']) ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Senha (deixe em branco para não alterar)</label>
                    <input type="password" name="senha" class="form-control">
                    <small class="text-muted">Se preenchida, será utilizada para alterar a senha</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Perfil</label>
                    <select name="perfil_id" class="form-select" required>
                        <?php foreach ($perfis as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= $usuarioEdit['perfil_id'] == $p['id'] ? 'selected' : '' ?>>
                                <?= htmlentities($p['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ativo" id="ativo" value="1" <?= $usuarioEdit['ativo'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="ativo">
                            Usuário ativo
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="<?= BASE_URL ?>index.php?page=usuarios" class="btn btn-outline-secondary">Cancelar</a>
                <?php if ($usuarioEdit['perfil_id'] != 1): ?>
                    <a href="<?= BASE_URL ?>index.php?page=usuarios_delete&id=<?= htmlentities($usuarioEdit['id']) ?>" class="btn btn-outline-danger ms-auto" onclick="return confirm('Tem certeza que deseja deletar este usuário?')">Deletar</a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>
