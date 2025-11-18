<?php

namespace App\Http\Controllers\Admin;

use App\Models\Venda;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ReportController extends Controller
{
    public function exportSales(Request $request, string $format)
    {
        $format = strtolower($format);
        abort_unless(in_array($format, ['csv', 'xlsx', 'pdf'], true), 404);

        $vendas = Venda::with(['produto', 'user'])->orderByDesc('created_at')->get();
        $dados = $this->mapSales($vendas);
        $agora = Carbon::now()->format('Y-m-d_H-i-s');

        if ($format === 'csv') {
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=relatorio-vendas-{$agora}.csv",
            ];

            $callback = function () use ($dados) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, "\xEF\xBB\xBF");
                fputcsv($handle, ['Produto', 'Cliente', 'Quantidade', 'Valor (R$)', 'Status', 'Data']);
                foreach ($dados as $linha) {
                    fputcsv($handle, $linha);
                }
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        if ($format === 'xlsx') {
            $headers = [
                'Content-Type' => 'application/vnd.ms-excel; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=relatorio-vendas-{$agora}.xls",
            ];

            $callback = function () use ($dados) {
                $handle = fopen('php://output', 'w');
                fprintf($handle, "\xEF\xBB\xBF");
                $header = ['Produto', 'Cliente', 'Quantidade', 'Valor (R$)', 'Status', 'Data'];
                fwrite($handle, implode("\t", $header)."\r\n");
                foreach ($dados as $linha) {
                    $linha = array_map(function ($valor) {
                        $valor = (string) $valor;

                        return str_replace(["\t", "\r", "\n"], ' ', $valor);
                    }, $linha);
                    fwrite($handle, implode("\t", $linha)."\r\n");
                }
                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
        }

        $options = new Options;
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        $dompdf = new Dompdf($options);
        $html = view('admin.reports.sales-pdf', [
            'linhas' => $dados,
        ])->render();
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('a4', 'portrait');
        $dompdf->render();

        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=relatorio-vendas-{$agora}.pdf",
        ]);
    }

    private function mapSales(Collection $vendas): Collection
    {
        return $vendas->map(function (Venda $venda) {
            return [
                $venda->produto->nome ?? 'Produto removido',
                $venda->user->name ?? 'Cliente removido',
                $venda->quantidade,
                number_format((float) $venda->valor_total, 2, ',', '.'),
                ucfirst($venda->status ?? 'indefinido'),
                optional($venda->created_at)->format('d/m/Y H:i'),
            ];
        });
    }
}
