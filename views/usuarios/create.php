<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Novo Usuário</h2>
    <a href="<?= BASE_URL ?>index.php?page=usuarios" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Novo Usuário</h5>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?= htmlentities($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?page=usuarios_store">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nome</label>
                    <input type="text" name="nome" class="form-control" value="<?= htmlentities($old['nome'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlentities($old['email'] ?? '') ?>" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Senha</label>
                    <input type="password" name="senha" class="form-control" required>
                    <small class="text-muted">Mínimo 6 caracteres</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Perfil</label>
                    <select name="perfil_id" class="form-select" required>
                        <option value="">Selecione um perfil</option>
                        <?php foreach ($perfis as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= isset($old['perfil_id']) && $old['perfil_id'] == $p['id'] ? 'selected' : '' ?>>
                                <?= htmlentities($p['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="ativo" id="ativo" value="1" <?= isset($old['ativo']) && $old['ativo'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="ativo">
                            Usuário ativo
                        </label>
                    </div>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Criar</button>
                <a href="<?= BASE_URL ?>index.php?page=usuarios" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
