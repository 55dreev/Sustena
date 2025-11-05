<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $uid  = $user->user_id ?? $user->id;

        // Latest & previous attempt totals (kg_per_week) for deltas
        $two = DB::table('footprint_scores')
            ->where('user_id', $uid)
            ->orderByDesc('id')->limit(2)
            ->get(['id','kg_per_week','created_at']);

        $latest = $two[0] ?? null;
        $prev   = $two[1] ?? null;

        // Category breakdown for latest attempt (join by attempt_id)
        $latestCats = collect();
        if ($latest) {
            $latestCats = DB::table('footprint_category_totals')
                ->where('attempt_id', $latest->id)
                ->get(['category','kg_per_week','total_score','created_at']);
        }

        // Suggest lifestyle fields from latest answers if you store them
        // Assumes a table footprint_answers(attempt_id, qkey, answer_text)
        $suggestions = [];
        if ($latest) {
            $answers = DB::table('footprint_answers')
                ->where('attempt_id', $latest->id)
                ->pluck('answer_text', 'qkey'); // e.g., diet, transport, home_type, energy_source
            $suggestions = [
                'diet'          => $answers['diet']          ?? null,
                'transport'     => $answers['transport']     ?? null,
                'home_type'     => $answers['home_type']     ?? null,
                'energy_source' => $answers['energy_source'] ?? null,
            ];
        }

        // Recent attempts for the small table
        $recent = DB::table('footprint_scores')
            ->where('user_id', $uid)
            ->orderByDesc('id')->limit(5)
            ->get(['id','kg_per_week','created_at']);

        // -----------------------
        // Energy metrics for Profile
        // -----------------------
        $energy_saved_kwh         = 0.0;  // month-only savings (≥ 0)
        $energy_change_kwh_signed = 0.0;  // month view signed change (+ saved, - up)
        $energy_delta_kwh_signed  = 0.0;  // vs previous attempt (+ saved vs last, - up)
        $energy_delta_kwh_abs     = 0.0;
        $energy_delta_direction   = 'flat'; // 'up' | 'down' | 'flat'

        if ($uid) {
            // Accept common labels for the electricity category
            $aliases = ['energy', 'electricity', 'electric'];

            $base = function () use ($uid, $aliases) {
                return DB::table('footprint_category_totals as ct')
                    ->join('footprint_scores as fs', 'fs.attempt_id', '=', 'ct.attempt_id')
                    ->where('fs.user_id', $uid)
                    ->whereIn(DB::raw('LOWER(ct.category)'), $aliases);
            };

            // (1) Month view: compare earliest vs latest in current month,
            // using last pre-month value as baseline if available.
            $monthStart = now()->startOfMonth();
            $monthEnd   = now()->endOfMonth();

            $baseline = $base()->where('fs.created_at', '<', $monthStart)
                ->orderBy('fs.created_at', 'desc')->value('ct.total_score');

            $earliestThisMonth = $base()->whereBetween('fs.created_at', [$monthStart, $monthEnd])
                ->orderBy('fs.created_at', 'asc')->value('ct.total_score');

            $latestThisMonth = $base()->whereBetween('fs.created_at', [$monthStart, $monthEnd])
                ->orderBy('fs.created_at', 'desc')->value('ct.total_score');

            if (!is_null($latestThisMonth) && (!is_null($baseline) || !is_null($earliestThisMonth))) {
                $startKg = !is_null($baseline) ? (float)$baseline : (float)$earliestThisMonth;
                $kg_change_signed = $startKg - (float)$latestThisMonth; // + saved, - up
                $kg_saved         = max(0.0, $kg_change_signed);

                // PH grid factor (kg CO₂ per kWh). Adjust if you have a better factor.
                $kg_per_kWh = 0.70;
                if ($kg_per_kWh > 0) {
                    $energy_change_kwh_signed = round($kg_change_signed / $kg_per_kWh, 1);
                    $energy_saved_kwh         = round($kg_saved        / $kg_per_kWh, 1);
                }
            }

            // (2) Versus previous attempt: latest vs previous energy category score
            $twoEnergy = $base()->orderBy('fs.created_at', 'desc')
                ->limit(2)->pluck('ct.total_score')->values();

            if ($twoEnergy->count() === 2) {
                $latestKg = (float)$twoEnergy[0];
                $prevKg   = (float)$twoEnergy[1];
                $kg_change_vs_last = $prevKg - $latestKg; // + saved vs last, - up

                $kg_per_kWh = 0.70;
                if ($kg_per_kWh > 0) {
                    $energy_delta_kwh_signed = round($kg_change_vs_last / $kg_per_kWh, 1);
                    $energy_delta_kwh_abs    = abs($energy_delta_kwh_signed);

                    if     ($energy_delta_kwh_signed > 0) $energy_delta_direction = 'down'; // saved vs last
                    elseif ($energy_delta_kwh_signed < 0) $energy_delta_direction = 'up';   // up vs last
                    else                                  $energy_delta_direction = 'flat';
                }
            }
        }

        return view('profilepage', [
            'user'        => $user,
            'latest'      => $latest,
            'prev'        => $prev,
            'latestCats'  => $latestCats,
            'suggestions' => $suggestions,
            'recent'      => $recent,

            // Energy metrics (for the Energy card in profile)
            'energy_saved_kwh'         => $energy_saved_kwh,
            'energy_change_kwh_signed' => $energy_change_kwh_signed,
            'energy_delta_kwh_signed'  => $energy_delta_kwh_signed,
            'energy_delta_kwh_abs'     => $energy_delta_kwh_abs,
            'energy_delta_direction'   => $energy_delta_direction,
        ]);
    }

    public function update(Request $r)
    {
        $data = $r->validate([
            'username'       => 'nullable|string|max:100',
            'email'          => 'required|email',
            'diet'           => 'nullable|string|max:50',
            'transport'      => 'nullable|string|max:80',
            'home_type'      => 'nullable|string|max:80',
            'energy_source'  => 'nullable|string|max:80',
            'weekly_target'  => 'nullable|integer|min:10|max:10000',
        ]);

        $u = Auth::user();
        if (!empty($data['username'])) {
            session(['username' => $data['username']]);
        }
        $u->email            = $data['email'];
        $u->diet             = $data['diet']          ?? $u->diet;
        $u->transport        = $data['transport']     ?? $u->transport;
        $u->home_type        = $data['home_type']     ?? $u->home_type;
        $u->energy_source    = $data['energy_source'] ?? $u->energy_source;
        $u->weekly_target_kg = $data['weekly_target'] ?? $u->weekly_target_kg;
        $u->save();

        return back()->with('ok', 'Profile updated');
    }

    public function autofill()
    {
        $u = Auth::user();
        $uid = $u->user_id ?? $u->id;

        $latestAttemptId = DB::table('footprint_scores')
            ->where('user_id', $uid)->orderByDesc('id')->value('id');

        if (!$latestAttemptId) return back()->with('err', 'No attempts yet');

        $a = DB::table('footprint_answers')
            ->where('attempt_id', $latestAttemptId)
            ->pluck('answer_text','qkey');

        $u->diet          = $a['diet']          ?? $u->diet;
        $u->transport     = $a['transport']     ?? $u->transport;
        $u->home_type     = $a['home_type']     ?? $u->home_type;
        $u->energy_source = $a['energy_source'] ?? $u->energy_source;
        $u->save();

        return back()->with('ok', 'Lifestyle info auto-filled from your latest attempt');
    }

    public function export(): StreamedResponse
    {
        $u = Auth::user();
        $uid = $u->user_id ?? $u->id;

        $rows = DB::table('footprint_scores')
            ->where('user_id', $uid)
            ->orderByDesc('created_at')
            ->limit(60)
            ->get(['created_at','kg_per_week']);

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['date','kg_per_week']);
            foreach ($rows as $r) fputcsv($out, [$r->created_at, $r->kg_per_week]);
            fclose($out);
        }, 'sustena_profile_export.csv');
    }
}
