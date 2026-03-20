<div class="row justify-content-center mt-5">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h3 class="mb-3">Login</h3>

                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger"><?= htmlentities($erro) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <input type="email" name="email" class="form-control mb-2" placeholder="Email" required>
                    <input type="password" name="senha" class="form-control mb-2" placeholder="Senha" required>
                    <button class="btn btn-primary w-100">Entrar</button>
                </form>
            </div>
        </div>
    </div>
</div>
