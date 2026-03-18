<?php
require_once __DIR__ . '/../models/Dashboard.php';
require_once __DIR__ . '/../core/Controller.php';

class DashboardController extends Controller
{
    public function index()
    {
        $this->proteger();

        $model = new Dashboard();

        // Filtros
        $insumo_id = $_GET['insumo_id'] ?? null;
        $lote_id = $_GET['lote_id'] ?? null;

        // Dados principais
        $perdasPorInsumo = $model->perdasPorInsumo();

        // 🔥 KPI GLOBAL DE PERDA (CORRETO)
        $totalPeso = 0;
        $totalPerda = 0;

        foreach ($perdasPorInsumo as $item) {
            $totalPeso += $item['peso_total'];
            $totalPerda += $item['perda_total'];
        }

        $percentualPerda = $totalPeso > 0 
            ? ($totalPerda * 100) / $totalPeso 
            : 0;

        $dados = [
            'insumos' => $model->totalInsumos(),
            'lotes' => $model->lotesAtivos(),
            'saldoTotal' => $model->saldoTotal(),
            'consumoMedio' => $model->consumoMedioPorChapa(),

            // ✅ corrigido aqui
            'percentualPerda' => $percentualPerda,

            'topLotes' => $model->topLotesConsumo(),
            'consumoMensal' => $model->consumoMensal(),
            'insumosPorConsumo' => $model->insumosPorConsumo(),
            'statusLotes' => $model->statusLotes(),
            'eficienciaConsumoPorChapa' => $model->eficienciaConsumoPorChapa(),

            'listaInsumos' => $model->obterInsumos(),
            'listaLotes' => $model->obterLotes(),

            // 🔍 filtros aplicados corretamente
            'perdasPorLote' => $model->perdasPorLote($insumo_id),
            'perdasPorInsumo' => $perdasPorInsumo,

            'consumoDiario' => $model->consumoDiario(),
            'perdaDiaria' => $model->perdaDiaria(),

            'filtroInsumo' => $insumo_id,
            'filtroLote' => $lote_id
        ];

        $this->view('dashboard/index', $dados);
    }
}