<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Dashboard</h1>
    <small class="text-muted">Última atualização: <?= date('d/m/Y H:i') ?></small>
</div>

<!-- Filtros -->
<div class="card shadow-sm border-0 mb-4">
    <div class="card-body">
        <h5 class="card-title">Filtros</h5>
        <form method="GET" action="<?= BASE_URL ?>index.php" class="row g-3">
            <input type="hidden" name="page" value="dashboard">
            
            <div class="col-md-6">
                <label class="form-label">Filtrar por Insumo</label>
                <select name="insumo_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Todos os insumos</option>
                    <?php foreach ($listaInsumos as $ins): ?>
                        <option value="<?= $ins['id'] ?>" <?= $filtroInsumo == $ins['id'] ? 'selected' : '' ?>>
                            <?= htmlentities($ins['descricao']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">Filtrar por Lote</label>
                <select name="lote_id" class="form-select" onchange="this.form.submit()">
                    <option value="">Todos os lotes</option>
                    <?php foreach ($listaLotes as $lote): ?>
                        <option value="<?= $lote['id'] ?>" <?= $filtroLote == $lote['id'] ? 'selected' : '' ?>>
                            <?= htmlentities($lote['lote']) ?> - <?= htmlentities($lote['insumo']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- KPIs -->
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted fw-bold">Insumos</h6>
                <div class="display-6 fw-bold text-primary"><?= number_format($insumos,0,',','.') ?></div>
                <small class="text-muted">Total cadastrado</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted fw-bold">Lotes Ativos</h6>
                <div class="display-6 fw-bold text-success"><?= number_format($lotes,0,',','.') ?></div>
                <small class="text-muted">Em processamento</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted fw-bold">Saldo Total</h6>
                <div class="display-6 fw-bold text-info"><?= number_format($saldoTotal,2,',','.') ?> kg</div>
                <small class="text-muted">Disponível</small>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6 col-lg-3">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h6 class="text-muted fw-bold">% Perda Geral</h6>
                <div class="display-6 fw-bold text-danger"><?= number_format($percentualPerda,2,',','.') ?>%</div>
                <small class="text-muted">Eficiência do processo</small>
            </div>
        </div>
    </div>
</div>

<!-- Gráficos -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5>Consumo Mensal</h5>
                <canvas id="consumoMensalChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5>Status dos Lotes</h5>
                <canvas id="statusLotesChart"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Perdas por Lote -->
<div class="card shadow-sm border-0">
    <div class="card-body">
        <h5>Análise de Perdas por Lote</h5>

        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th>Lote</th>
                    <th>Insumo</th>
                    <th class="text-end">Peso Inicial</th>
                    <th class="text-end">Consumo</th>
                    <th class="text-end">Saldo Teórico</th>
                    <th class="text-end">Saldo Real</th>
                    <th class="text-end">Perda (kg)</th>
                    <th class="text-end">% Perda</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($perdasPorLote as $p): ?>
                    <tr>
                        <td><?= $p['lote'] ?></td>
                        <td><?= $p['insumo'] ?></td>

                        <td class="text-end"><?= number_format($p['peso_inicial'],2,',','.') ?></td>
                        <td class="text-end"><?= number_format($p['consumo_total'],2,',','.') ?></td>
                        <td class="text-end"><?= number_format($p['saldo_teorico'],2,',','.') ?></td>
                        <td class="text-end"><?= number_format($p['saldo_real'],2,',','.') ?></td>

                        <td class="text-end">
                            <span class="<?= $p['perda'] > 5 ? 'text-danger fw-bold' : ($p['perda'] > 0 ? 'text-warning fw-bold' : 'text-success') ?>">
                                <?= number_format($p['perda'],2,',','.') ?>
                            </span>
                        </td>

                        <td class="text-end">
                            <span class="<?= $p['percentual_perda'] > 5 ? 'text-danger fw-bold' : 'text-success' ?>">
                                <?= number_format($p['percentual_perda'],2,',','.') ?>%
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Perdas por Insumo -->
<div class="card shadow-sm border-0 mt-4">
    <div class="card-body">
        <h5>Análise de Perdas por Insumo</h5>

        <table class="table table-sm table-hover">
            <thead>
                <tr>
                    <th>Insumo</th>
                    <th class="text-end">Peso Total</th>
                    <th class="text-end">Consumo</th>
                    <th class="text-end">Perda Total</th>
                    <th class="text-end">% Perda</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($perdasPorInsumo as $pi): ?>
                    <tr>
                        <td><?= $pi['insumo'] ?></td>
                        <td class="text-end"><?= number_format($pi['peso_total'],2,',','.') ?></td>
                        <td class="text-end"><?= number_format($pi['consumo_total'],2,',','.') ?></td>
                        <td class="text-end"><?= number_format($pi['perda_total'],2,',','.') ?></td>

                        <td class="text-end">
                            <span class="<?= $pi['percentual_perda'] > 5 ? 'text-danger fw-bold' : 'text-success' ?>">
                                <?= number_format($pi['percentual_perda'],2,',','.') ?>%
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
new Chart(document.getElementById('eficienciaChart'), {
    type: 'line',
    data: {
        labels: [<?php foreach ($eficienciaConsumoPorChapa as $item): ?>'<?= $item['mes'] ?>',<?php endforeach; ?>],
        datasets: [{
            label: 'Consumo médio',
            data: [<?php foreach ($eficienciaConsumoPorChapa as $item): ?><?= $item['consumo_medio'] ?>,<?php endforeach; ?>],
            fill: true
        }]
    }
});
</script>