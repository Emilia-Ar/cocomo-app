<?php

namespace App\Http\Controllers;

use App\Http\Requests\EstimateRequest;
use App\Models\Estimate;
use App\Services\CocomoService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CocomoController extends Controller
{
    public function index()
    {
        $history = Estimate::latest()->paginate(10);
        $defaults = array_fill_keys(
            ['RELY','DATA','CPLX','TIME','STOR','VIRT','TURN','ACAP','AEXP','PCAP','VEXP','LTEX','MODP','TOOL','SCED'],
            'nominal'
        );

        return view('cocomo.index', compact('history', 'defaults'));
    }

    public function estimate(EstimateRequest $request)
    {
        $data = $request->validated();
        $drivers = [];
        foreach (['RELY','DATA','CPLX','TIME','STOR','VIRT','TURN','ACAP','AEXP','PCAP','VEXP','LTEX','MODP','TOOL','SCED'] as $k) {
            $drivers[$k] = $data[$k];
        }

        [$eaf,$pm,$tdev,$p,$monthly,$total] = CocomoService::estimate(
            (float)$data['kloc'],
            $data['mode'],
            (float)$data['salary'],
            $drivers
        );

        Estimate::create([
            'kloc' => $data['kloc'],
            'mode' => $data['mode'],
            'salary' => $data['salary'],
            'drivers' => $drivers,
            'eaf' => $eaf,
            'pm' => $pm,
            'tdev' => $tdev,
            'p' => $p,
            'monthly_cost' => $monthly,
            'total_cost' => $total,
        ]);

        return redirect()->route('cocomo.index')->with('result', [
            'eaf'=>$eaf,'pm'=>$pm,'tdev'=>$tdev,'p'=>$p,'monthly'=>$monthly,'total'=>$total,
        ]);
    }

    public function exportCsv(): StreamedResponse
    {
        $fileName = 'estimates_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
        ];
        $columns = ['id','kloc','mode','salary','eaf','pm','tdev','p','monthly_cost','total_cost','drivers','created_at'];

        $callback = function() use ($columns) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $columns);
            \App\Models\Estimate::orderBy('id')->chunk(200, function($rows) use ($handle, $columns) {
                foreach ($rows as $r) {
                    $row = [];
                    foreach ($columns as $c) {
                        $row[] = $c === 'drivers' ? json_encode($r->drivers) : $r->$c;
                    }
                    fputcsv($handle, $row);
                }
            });
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
