<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Editar Execução de Processo</h2>
    <a href="<?= BASE_URL ?>index.php?page=execucoes_processos" class="btn btn-sm btn-outline-secondary">Voltar</a>
</div>

<div class="card">
    <div class="card-body">
        <h5 class="card-title">Editar Execução de Processo</h5>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?= htmlentities($erro) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>index.php?page=execucoes_processos_update">
            <input type="hidden" name="id" value="<?= htmlentities($execucao['id']) ?>">

            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Processo</label>
                    <select name="processo_id" class="form-select" required>
                        <?php foreach ($processos as $p): ?>
                            <option value="<?= $p['id'] ?>" <?= $execucao['processo_id'] == $p['id'] ? 'selected' : '' ?>><?= htmlentities($p['nome']) ?> (<?= htmlentities($p['codigo']) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Bloco</label>
                    <select name="bloco_id" class="form-select" required>
                        <?php foreach ($blocos as $b): ?>
                            <option value="<?= $b['id'] ?>" <?= $execucao['bloco_id'] == $b['id'] ? 'selected' : '' ?>><?= htmlentities($b['codigo']) ?> - <?= htmlentities($b['material_nome']) ?> (<?= $b['numero_chapas'] ?> chapas)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Data</label>
                    <input type="date" name="data_execucao" class="form-control" value="<?= htmlentities($execucao['data_execucao']) ?>" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Quantidade de Chapas</label>
                    <input type="number" name="qtd_chapas" class="form-control" value="<?= htmlentities($execucao['qtd_chapas']) ?>" required>
                </div>
            </div>

            <h6 class="mt-4">Insumos Utilizados</h6>
            <div id="insumos-container">
                <?php if (!empty($execucao['insumos'])): ?>
                    <?php foreach ($execucao['insumos'] as $index => $insumo): ?>
                        <div class="insumo-row row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Lote</label>
                                <select name="lote_id[]" class="form-select" required>
                                    <option value="">Selecione um lote</option>
                                    <?php foreach ($lotes as $l): ?>
                                        <option value="<?= $l['id'] ?>" <?= $insumo['lote_id'] == $l['id'] ? 'selected' : '' ?>><?= htmlentities($l['lote']) ?> - <?= htmlentities($l['insumo_nome']) ?> (Saldo: <?= number_format($l['saldo_atual'], 2, ',', '.') ?> kg)</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Quantidade por Chapa (g)</label>
                                <input type="number" step="0.01" name="quantidade_por_chapa[]" class="form-control" value="<?= htmlentities($insumo['quantidade_por_chapa']) ?>" required>
                            </div>
                            <div class="col-md-1 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-insumo">&times;</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="insumo-row row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Lote</label>
                            <select name="lote_id[]" class="form-select" required>
                                <option value="">Selecione um lote</option>
                                <?php foreach ($lotes as $l): ?>
                                    <option value="<?= $l['id'] ?>"><?= htmlentities($l['lote']) ?> - <?= htmlentities($l['insumo_nome']) ?> (Saldo: <?= number_format($l['saldo_atual'], 2, ',', '.') ?> kg)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Quantidade por Chapa (g)</label>
                            <input type="number" step="0.01" name="quantidade_por_chapa[]" class="form-control" required>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-insumo" style="display: none;">&times;</button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <button type="button" id="add-insumo" class="btn btn-outline-primary btn-sm">Adicionar Insumo</button>

            <div class="mt-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="<?= BASE_URL ?>index.php?page=execucoes_processos" class="btn btn-outline-secondary">Cancelar</a>
                <form method="POST" action="<?= BASE_URL ?>index.php?page=execucoes_processos_delete" style="display: inline;">
                    <input type="hidden" name="id" value="<?= htmlentities($execucao['id']) ?>">
                    <button type="submit" class="btn btn-outline-danger ms-auto" onclick="return confirm('Tem certeza que deseja deletar esta execução?')">Deletar</button>
                </form>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('add-insumo').addEventListener('click', function() {
    const container = document.getElementById('insumos-container');
    const firstRow = container.querySelector('.insumo-row');
    const newRow = firstRow.cloneNode(true);

    // Limpar valores
    newRow.querySelectorAll('input').forEach(input => input.value = '');
    newRow.querySelector('select').selectedIndex = 0;

    // Mostrar botão de remover
    newRow.querySelector('.remove-insumo').style.display = 'block';

    container.appendChild(newRow);
    updateRemoveButtons();
});

function updateRemoveButtons() {
    const rows = document.querySelectorAll('.insumo-row');
    rows.forEach((row, index) => {
        const removeBtn = row.querySelector('.remove-insumo');
        if (rows.length > 1) {
            removeBtn.style.display = 'block';
            removeBtn.onclick = function() {
                row.remove();
                updateRemoveButtons();
            };
        } else {
            removeBtn.style.display = 'none';
        }
    });
}

// Inicializar
updateRemoveButtons();
</script>