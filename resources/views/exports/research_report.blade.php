<!-- resources/views/exports/research_report.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>SUSTENA – Footprint Report</title>
    <style>
        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 11px;
            color: #111827;
        }
        h1,h2,h3,h4 {
            margin: 0 0 4px 0;
        }
        h1 { font-size: 20px; }
        h2 { font-size: 14px; margin-top: 12px; }
        h3 { font-size: 12px; margin-top: 8px; }

        .muted { color:#6b7280; font-size:10px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 4px 6px;
            text-align: left;
        }
        th {
            background: #f3f4f6;
            font-weight: bold;
        }
        .no-border td, .no-border th { border: none; }

        .section {
            margin-top: 14px;
        }

        .pill {
            display:inline-block;
            padding:2px 6px;
            border-radius:999px;
            font-size:9px;
            background:#e5e7eb;
        }

        /* Simple bar visuals for DomPDF */
        .bar-cell {
            width: 100%;
            background: #f3f4f6;
        }
        .bar {
            height: 8px;
            background: #9ca3af;
        }

        .small { font-size: 9px; }

        .matrix-cell {
            text-align: right;
            font-size: 9px;
        }
    </style>
</head>
<body>
@php
    $summary   = $p['summary']   ?? [];
    $cards     = $p['by_category'] ?? [];
    $trend     = $p['trend']     ?? [];
    $series    = $p['timeseries'] ?? [];
    $meta      = $p['meta']      ?? [];
    $export    = $meta['export'] ?? [];

    $total     = (float)($summary['total'] ?? 0);
    $deltaPct  = $summary['delta_pct'] ?? null;

    $dateFrom  = $export['date_from'] ?? null;
    $dateTo    = $export['date_to']   ?? null;
    $scope     = $export['scope']     ?? ($meta['user_role'] ?? 'user');
    $basis     = $export['basis']     ?? 'weekly';
    $tz        = $meta['timezone']    ?? 'Asia/Manila';
    $units     = $meta['units']['amount'] ?? 'kg CO₂ / wk';
    $generated = $meta['generated_at_iso'] ?? now()->toIso8601String();

    // ---- basic helpers ----
    function arr_median(array $a) {
        $n = count($a);
        if ($n === 0) return null;
        sort($a, SORT_NUMERIC);
        $mid = (int) floor(($n-1)/2);
        if ($n % 2) return $a[$mid];
        return ($a[$mid] + $a[$mid+1]) / 2;
    }

    function arr_stddev(array $a) {
        $n = count($a);
        if ($n <= 1) return null;
        $mean = array_sum($a)/$n;
        $var = 0;
        foreach ($a as $v) {
            $var += ($v - $mean) * ($v - $mean);
        }
        return sqrt($var / ($n-1));
    }

    function pearson_corr(array $x, array $y) {
        $n = min(count($x), count($y));
        if ($n <= 1) return null;
        $x = array_slice($x, 0, $n);
        $y = array_slice($y, 0, $n);

        $meanX = array_sum($x) / $n;
        $meanY = array_sum($y) / $n;

        $num = 0; $denX = 0; $denY = 0;
        for ($i=0;$i<$n;$i++) {
            $dx = $x[$i] - $meanX;
            $dy = $y[$i] - $meanY;
            $num  += $dx * $dy;
            $denX += $dx * $dx;
            $denY += $dy * $dy;
        }
        if ($denX <= 0 || $denY <= 0) return null;
        return $num / sqrt($denX * $denY);
    }

    /**
     * Build a unified trendRows array:
     * - use $trend.total if present
     * - otherwise fall back to $series.total_weekly
     */
    $trendRows = [];
    if (!empty($trend)) {
        foreach ($trend as $row) {
            $trendRows[] = [
                'date'  => $row['date'] ?? null,
                'total' => (float)($row['total'] ?? 0),
            ];
        }
    } elseif (!empty($series)) {
        foreach ($series as $row) {
            $trendRows[] = [
                'date'  => $row['date'] ?? null,
                'total' => (float)($row['total_weekly'] ?? 0),
            ];
        }
    }

    // ---- data for stats ----
    $totalsAll        = [];
    $trendNonZeroRows = [];
    foreach ($trendRows as $row) {
        $t = (float)($row['total'] ?? 0);
        $totalsAll[] = $t;
        if ($t > 0) {
            $trendNonZeroRows[] = $row;
        }
    }
    $nonZeroVals   = array_map(fn($r) => (float)$r['total'], $trendNonZeroRows);

    $countPoints   = count($trendRows);
    $countNonZero  = count($trendNonZeroRows);

    $avgTotal      = $countNonZero ? array_sum($nonZeroVals) / $countNonZero : null;
    $medianTotal   = $countNonZero ? arr_median($nonZeroVals) : null;
    $stdTotal      = $countNonZero ? arr_stddev($nonZeroVals) : null;
    $minTotal      = $countNonZero ? min($nonZeroVals) : null;
    $maxTotal      = $countNonZero ? max($nonZeroVals) : null;

    // coverage vs date range
    $coveragePct = null;
    if ($dateFrom && $dateTo) {
        $d1 = strtotime($dateFrom);
        $d2 = strtotime($dateTo);
        if ($d1 && $d2 && $d2 >= $d1) {
            $days = (int) floor(($d2 - $d1) / 86400) + 1;
            if ($days > 0) {
                $coveragePct = round($countPoints / $days * 100, 1);
            }
        }
    }

    // peak / low days
    $sortedDesc = $trendNonZeroRows;
    usort($sortedDesc, function($a,$b){
        return ($b['total'] ?? 0) <=> ($a['total'] ?? 0);
    });
    $top3 = array_slice($sortedDesc, 0, 3);

    $sortedAsc = $trendNonZeroRows;
    usort($sortedAsc, function($a,$b){
        return ($a['total'] ?? 0) <=> ($b['total'] ?? 0);
    });
    $bottom3 = array_slice($sortedAsc, 0, 3);

    // collect category list and series
    $categorySet = [];
    foreach ($series as $row) {
        $cats = $row['categories'] ?? [];
        foreach ($cats as $catName => $val) {
            $categorySet[$catName] = true;
        }
    }
    $categories = array_keys($categorySet);
    sort($categories);

    $categorySeries = [];
    foreach ($categories as $cat) {
        $categorySeries[$cat] = [];
    }
    foreach ($series as $row) {
        $cats = $row['categories'] ?? [];
        foreach ($categories as $cat) {
            $categorySeries[$cat][] = (float)($cats[$cat] ?? 0);
        }
    }

    // averages per category
    $categoryAverages = [];
    foreach ($categories as $cat) {
        $vals = array_filter($categorySeries[$cat], fn($v) => $v != 0);
        $categoryAverages[$cat] = count($vals)
            ? array_sum($vals)/count($vals)
            : 0;
    }

    // weekday pattern from trendRows totals
    $weekdayTotals = [1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0];
    $weekdayCounts = [1=>0,2=>0,3=>0,4=>0,5=>0,6=>0,7=>0];
    foreach ($trendRows as $row) {
        if (empty($row['date'])) continue;
        $ts = strtotime($row['date']);
        if (!$ts) continue;
        $w = (int) date('N', $ts); // 1..7
        $val = (float)($row['total'] ?? 0);
        $weekdayTotals[$w] += $val;
        $weekdayCounts[$w] += 1;
    }
    $weekdayNames = [1=>'Mon',2=>'Tue',3=>'Wed',4=>'Thu',5=>'Fri',6=>'Sat',7=>'Sun'];

    // correlation matrix
    $corrMatrix = [];
    foreach ($categories as $i => $catA) {
        $corrMatrix[$catA] = [];
        foreach ($categories as $j => $catB) {
            if ($i === $j) {
                $corrMatrix[$catA][$catB] = 1.0;
            } else {
                $c = pearson_corr($categorySeries[$catA], $categorySeries[$catB]);
                $corrMatrix[$catA][$catB] = $c;
            }
        }
    }

    // helper for bar width
    function pct_width($val, $max) {
        if ($max <= 0) return 0;
        $w = ($val / $max) * 100;
        return max(0, min(100, $w));
    }

    $maxTrendValue = $countNonZero ? $maxTotal : 0;
@endphp

<h1>SUSTENA – Footprint Report</h1>
<p class="muted">
    Generated: {{ $generated }} | TZ: {{ $tz }}
    | Basis: {{ $basis }} | Range:
    @if($dateFrom && $dateTo)
        {{ $dateFrom }} .. {{ $dateTo }}
    @else
        n/a
    @endif
    | Scope: {{ $scope }}
</p>

{{-- ================= SUMMARY ================= --}}
<div class="section">
    <h2>Summary</h2>
    <table>
        <tr>
            <th style="width:30%;">Total</th>
            <th style="width:30%;">Change vs last (%)</th>
            <th style="width:40%;">Units</th>
        </tr>
        <tr>
            <td>{{ number_format($total, 2) }}</td>
            <td>{{ is_null($deltaPct) ? 'n/a' : number_format($deltaPct, 2) }}</td>
            <td>{{ $units }}</td>
        </tr>
    </table>
</div>

{{-- ================= DATA COMPLETENESS ================= --}}
<div class="section">
    <h2>Data Completeness</h2>
    <table>
        <tr>
            <th>Metric</th>
            <th>Value</th>
        </tr>
        <tr>
            <td>Number of observations (time points)</td>
            <td>{{ $countPoints }}</td>
        </tr>
        <tr>
            <td>Non-zero observations</td>
            <td>{{ $countNonZero }}</td>
        </tr>
        <tr>
            <td>Date range coverage</td>
            <td>
                @if(!is_null($coveragePct) && $dateFrom && $dateTo)
                    {{ $coveragePct }}% of days between {{ $dateFrom }} and {{ $dateTo }}
                @else
                    n/a
                @endif
            </td>
        </tr>
    </table>
</div>

{{-- ================= BY CATEGORY ================= --}}
<div class="section">
    <h2>By category</h2>
    <table>
        <tr>
            <th>Category</th>
            <th>Share (%)</th>
            <th>Amount</th>
        </tr>
        @foreach ($cards as $row)
            <tr>
                <td>{{ $row['category'] ?? $row['title'] ?? 'n/a' }}</td>
                <td>{{ number_format($row['share_pct'] ?? $row['percent'] ?? 0, 2) }}</td>
                <td>{{ number_format($row['amount'] ?? $row['kg_per_week'] ?? 0, 2) }}</td>
            </tr>
        @endforeach
    </table>
</div>

{{-- ================= INSIGHT SUMMARY ================= --}}
<div class="section">
    <h2>Insight Summary</h2>
    <table class="no-border">
        <tr>
            <td>
                <strong>Dominant categories:</strong>
                @php
                    $sortedCards = $cards;
                    usort($sortedCards, fn($a,$b) =>
                        ($b['share_pct'] ?? $b['percent'] ?? 0)
                        <=>
                        ($a['share_pct'] ?? $a['percent'] ?? 0)
                    );
                    $topCats = array_slice($sortedCards, 0, 3);
                @endphp
                @if(count($topCats))
                    @foreach($topCats as $idx => $c)
                        {{ $idx ? ', ' : '' }}{{ $c['category'] ?? $c['title'] ?? 'n/a' }}
                        ({{ number_format($c['share_pct'] ?? $c['percent'] ?? 0, 1) }}%)
                    @endforeach
                @else
                    n/a
                @endif
                <br>
                <strong>Highest recorded footprint:</strong>
                @if(count($top3))
                    {{ $top3[0]['date'] ?? '' }}
                    — {{ number_format($top3[0]['total'], 2) }} {{ $units }}
                @else
                    n/a
                @endif
                <br>
                <strong>Average non-zero observation:</strong>
                {{ $avgTotal !== null ? number_format($avgTotal, 2) : 'n/a' }} {{ $units }}
            </td>
        </tr>
    </table>
</div>

{{-- ================= TREND (TOTAL) ================= --}}
<div class="section">
    <h2>Trend (weekly normalized)</h2>
    <table>
        <tr>
            <th style="width:20%;">Date</th>
            <th style="width:20%;">Value</th>
            <th style="width:60%;">Visual</th>
        </tr>
        @foreach ($trendRows as $row)
            @php
                $v = (float)($row['total'] ?? 0);
                $w = pct_width($v, $maxTrendValue);
            @endphp
            <tr>
                <td>{{ $row['date'] ?? '' }}</td>
                <td>{{ number_format($v, 2) }}</td>
                <td>
                    <div class="bar-cell">
                        <div class="bar" style="width: {{ $w }}%;"></div>
                    </div>
                </td>
            </tr>
        @endforeach
    </table>
</div>

{{-- ================= PEAK / LOW DAYS ================= --}}
<div class="section">
    <h2>Peak and Low Activity Days</h2>
    <table>
        <tr>
            <th colspan="2">Top 3 peak days</th>
            <th colspan="2">Lowest 3 non-zero days</th>
        </tr>
        <tr>
            <th>Date</th><th>Value</th>
            <th>Date</th><th>Value</th>
        </tr>
        @for ($i = 0; $i < 3; $i++)
            <tr>
                <td>{{ $top3[$i]['date'] ?? '' }}</td>
                <td>{{ isset($top3[$i]) ? number_format($top3[$i]['total'], 2) : '' }}</td>
                <td>{{ $bottom3[$i]['date'] ?? '' }}</td>
                <td>{{ isset($bottom3[$i]) ? number_format($bottom3[$i]['total'], 2) : '' }}</td>
            </tr>
        @endfor
    </table>
</div>

{{-- ================= USER BEHAVIOR METRICS ================= --}}
<div class="section">
    <h2>User behavior metrics (aggregated)</h2>
    <table>
        <tr>
            <th>Metric</th>
            <th>Value (non-zero observations)</th>
        </tr>
        <tr>
            <td>Average</td>
            <td>{{ $avgTotal   !== null ? number_format($avgTotal,   2) : 'n/a' }} {{ $units }}</td>
        </tr>
        <tr>
            <td>Median</td>
            <td>{{ $medianTotal!== null ? number_format($medianTotal,2) : 'n/a' }} {{ $units }}</td>
        </tr>
        <tr>
            <td>Standard deviation</td>
            <td>{{ $stdTotal   !== null ? number_format($stdTotal,   2) : 'n/a' }} {{ $units }}</td>
        </tr>
        <tr>
            <td>Minimum</td>
            <td>{{ $minTotal   !== null ? number_format($minTotal,   2) : 'n/a' }} {{ $units }}</td>
        </tr>
        <tr>
            <td>Maximum</td>
            <td>{{ $maxTotal   !== null ? number_format($maxTotal,   2) : 'n/a' }} {{ $units }}</td>
        </tr>
    </table>
</div>

{{-- ================= WEEKDAY PATTERN ================= --}}
<div class="section">
    <h2>Average footprint by weekday</h2>
    <table>
        <tr>
            <th>Weekday</th>
            <th>Average value</th>
        </tr>
        @foreach ($weekdayNames as $idx => $name)
            @php
                $c = $weekdayCounts[$idx] ?? 0;
                $avg = $c ? $weekdayTotals[$idx] / $c : null;
            @endphp
            <tr>
                <td>{{ $name }}</td>
                <td>
                    @if($avg !== null)
                        {{ number_format($avg, 2) }} {{ $units }}
                    @else
                        n/a
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</div>

{{-- ================= CATEGORY TRENDS (TABLE) ================= --}}
@if (count($categories))
<div class="section">
    <h2>Category trends over time</h2>
    <table>
        <tr>
            <th>Date</th>
            @foreach ($categories as $cat)
                <th>{{ $cat }}</th>
            @endforeach
        </tr>
        @foreach ($series as $row)
            <tr>
                <td>{{ $row['date'] ?? '' }}</td>
                @php $cats = $row['categories'] ?? []; @endphp
                @foreach ($categories as $cat)
                    <td>{{ number_format((float)($cats[$cat] ?? 0), 2) }}</td>
                @endforeach
            </tr>
        @endforeach
    </table>
</div>
@endif

{{-- ================= CORRELATION MATRIX ================= --}}
@if (count($categories) >= 2)
<div class="section">
    <h2>Category correlation matrix (Pearson)</h2>
    <table>
        <tr>
            <th></th>
            @foreach ($categories as $cat)
                <th class="matrix-cell">{{ $cat }}</th>
            @endforeach
        </tr>
        @foreach ($categories as $rowCat)
            <tr>
                <th>{{ $rowCat }}</th>
                @foreach ($categories as $colCat)
                    @php
                        $c = $corrMatrix[$rowCat][$colCat] ?? null;
                    @endphp
                    <td class="matrix-cell">
                        @if (is_null($c))
                            n/a
                        @else
                            {{ number_format($c, 2) }}
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
    <p class="small muted">
        Values near +1 indicate strong positive correlation, values near −1 indicate strong negative
        correlation, and values near 0 indicate little or no linear relationship. Correlations are
        computed from the time-series category values in the selected range.
    </p>
</div>
@endif

{{-- ================= METHODOLOGY & NOTES ================= --}}
<div class="section">
    <h2>Methodology</h2>
    <p class="small">
        Values are normalized to the selected basis ({{ $basis }}); raw server values are stored
        as weekly totals. Emission factors are based on documented sources such as IPCC, DEFRA,
        or locally adapted factors where available. UI categories are mapped and summed from raw
        database categories (e.g. <code>footprint_category_totals</code>).
    </p>
    <p class="small">
        Self-reported activities may contain estimation error. Practice or non-official attempts
        may be included depending on export settings. Negative values in some categories (for
        example Waste Management or Water Usage) can represent recycling credits, corrections, or
        compensating reductions.
    </p>
    <p class="small">
        This report is intended for research and educational purposes. For policy use or external
        publication, researchers should document exact emission factors, data cleaning steps,
        and any additional filtering applied on top of this export.
    </p>
</div>

</body>
</html>
