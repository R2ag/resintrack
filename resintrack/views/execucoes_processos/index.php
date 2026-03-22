<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Execuções de Processos</h2>
    <a href="<?= BASE_URL ?>index.php?page=execucoes_processos_create" class="btn btn-sm btn-primary">Nova Execução</a>
</div>

<div class="card">
  <div class="card-body">
    <?php if (empty($execucoes)): ?>
      <div class="alert alert-secondary">Nenhuma execução registrada.</div>
    <?php else: ?>
      <div class="table-responsive">
        <table class="table table-sm table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
                <th>Data</th>
                <th>Processo</th>
                <th>Bloco</th>
                <th>Material</th>
                <th class="text-end">Qtd Chapas</th>
                <th>Insumos Utilizados</th>
                <th class="text-center" style="width: 120px">Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($execucoes as $e): ?>
            <tr>
                <td><?= date('d/m/Y H:i', strtotime($e['data_execucao'])) ?></td>
                <td><?= htmlentities($e['processo_nome']) ?> (<?= htmlentities($e['processo_codigo']) ?>)</td>
                <td><?= htmlentities($e['bloco_codigo']) ?></td>
                <td><?= htmlentities($e['material_nome']) ?></td>
                <td class="text-end"><?= $e['qtd_chapas'] ?></td>
                <td>
                    <?php
                    // Buscar insumos para esta execução
                    $execucaoModel = new ExecucaoProcesso();
                    $execucaoCompleta = $execucaoModel->buscarPorId($e['id']);
                    $insumos = $execucaoCompleta['insumos'];
                    $insumosTexto = [];
                    foreach ($insumos as $i) {
                        $insumosTexto[] = $i['lote'] . ' (' . number_format($i['quantidade_por_chapa'], 2, ',', '.') . ' g/chapa)';
                    }
                    echo implode(', ', $insumosTexto);
                    ?>
                </td>
                <td class="text-center">
                    <a href="<?= BASE_URL ?>index.php?page=execucoes_processos_edit&id=<?= htmlentities($e['id']) ?>" class="btn btn-xs btn-warning" title="Editar">
                        <i class="bi bi-pencil"></i> Editar
                    </a>
                    <form method="POST" action="<?= BASE_URL ?>index.php?page=execucoes_processos_delete" style="display: inline;">
                        <input type="hidden" name="id" value="<?= htmlentities($e['id']) ?>">
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