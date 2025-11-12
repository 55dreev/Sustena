<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;        // composer package
use ZipStream\ZipStream;               // composer package

class ResearchExportController extends Controller
{
    public function pdf(Request $req)
{
    try {
        $payload = $req->all();
        // Ensure view exists at resources/views/exports/research_report.blade.php
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('exports.research_report', ['p' => $payload])
                ->setPaper('a4', 'portrait');  // optional but helps

        return $pdf->download('sustena_report_'.now()->timestamp.'.pdf');
    } catch (\Throwable $e) {
        \Log::error('PDF export failed', ['err' => $e->getMessage()]);
        return response('PDF generation failed: '.$e->getMessage(), 500);
    }
}


    public function zip(Request $req)
    {
        $payload = $req->all();

        // Optional anonymization of user id
        if (data_get($payload, 'meta.export.anonymized')) {
            $uid = (string) data_get($payload, 'meta.export.user_id');
            data_set($payload, 'meta.export.user_id', $this->anonId($uid));
        }

        $now = now()->timestamp;
        $csv = $this->toCsv($payload);
        $json = json_encode($payload, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        $readme = $this->readme($payload);
        $dict = $this->dictionary();
        $method = $this->methodology();

        $zip = new ZipStream('sustena_research_'.$now.'.zip');
        $zip->addFile('data/sustena_export_'.$now.'.csv', $csv);
        $zip->addFile('data/sustena_export_'.$now.'.json', $json);
        $zip->addFile('docs/README.txt', $readme);
        $zip->addFile('docs/DATA_DICTIONARY.txt', $dict);
        $zip->addFile('docs/METHODOLOGY.txt', $method);
        $zip->finish();
    }

    private function anonId(?string $id): string
    {
        $key = config('app.key') ?: 'sustena';
        return substr(hash_hmac('sha256', (string) $id, $key), 0, 16);
    }

    private function toCsv(array $p): string
    {
        $m = $p['meta'] ?? [];
        $L = [];
        $L[] = '# SUSTENA Research Export';
        $L[] = '# generated_at=' . ($m['generated_at_iso'] ?? '');
        $L[] = '# timezone=' . ($m['timezone'] ?? '');
        $L[] = '# app_version=' . ($m['app_version'] ?? '');
        $L[] = '# scope=' . (data_get($m, 'export.scope', ''));
        $L[] = '# basis=' . (data_get($m, 'export.basis', ''));
        $L[] = '# date_from=' . (data_get($m, 'export.date_from', ''));
        $L[] = '# date_to=' . (data_get($m, 'export.date_to', ''));
        $L[] = '# anonymized=' . (data_get($m, 'export.anonymized') ? 'true' : 'false');
        $L[] = '# units.amount=' . (data_get($m, 'units.amount', 'kg CO2 / wk'));
        $L[] = '';

        $L[] = 'SECTION,category,share_pct,amount,unit,date,value';

        foreach (($p['by_category'] ?? []) as $r) {
            $L[] = implode(',', [
                'by_category',
                $this->esc($r['category'] ?? ''),
                ($r['share_pct'] ?? ''),
                ($r['amount'] ?? ''),
                $this->esc($r['unit'] ?? 'kg CO2 / wk'),
                '', ''
            ]);
        }
        foreach (($p['trend'] ?? []) as $t) {
            $L[] = implode(',', ['trend','','','','', ($t['date'] ?? ''), ($t['value'] ?? '')]);
        }

        $L[] = 'summary_total,,,' . (data_get($p, 'summary.total', 0)) . ',' . (data_get($m, 'units.amount', '')) . ',,';
        $L[] = 'summary_delta_pct,,,' . (data_get($p, 'summary.delta_pct', 0)) . ',pct,,';
        return implode("\n", $L);
    }

    private function esc(string $s): string
    {
        return str_contains($s, ',') ? '"' . str_replace('"','""',$s) . '"' : $s;
    }

    private function readme(array $p): string
    {
        return <<<TXT
SUSTENA – Research bundle

FILES
- data/sustena_export.csv      Row/section CSV with meta header
- data/sustena_export.json     Rich JSON with metadata and dictionary
- docs/DATA_DICTIONARY.txt     Field definitions
- docs/METHODOLOGY.txt         Scaling, factors, aggregation notes

SCOPE
- scope:    {data_get($p,'meta.export.scope')}
- basis:    {data_get($p,'meta.export.basis')}
- range:    {data_get($p,'meta.export.date_from')} .. {data_get($p,'meta.export.date_to')}
- timezone: {data_get($p,'meta.timezone')}
- anonymized: {data_get($p,'meta.export.anonymized')}

CONTACT
- generated_at: {data_get($p,'meta.generated_at_iso')}
- app_version:  {data_get($p,'meta.app_version')}
TXT;
    }

    private function dictionary(): string
    {
        return <<<TXT
DATA DICTIONARY
category       : Footprint category (UI bucket)
share_pct      : Percent share of total (%)
amount         : CO2 amount for the row (kg CO2 / week)
date           : ISO date for time-series points
value          : Footprint value at date (weekly normalized)

SUMMARY
summary_total     : Total footprint (see units)
summary_delta_pct : Percent change vs previous period
TXT;
    }

    private function methodology(): string
    {
        return <<<TXT
METHODOLOGY
Scaling: Values normalized to the selected basis (client display); server values weekly.
Emission factors source: Document the reference (IPCC/DEFRA/local factors).
Aggregation: UI categories mapped and summed from raw DB categories.
Data quality: Self-reported inputs; practice attempts may be included if enabled.
Privacy: When anonymized, user ID is hashed and PII excluded.
TXT;
    }
}
