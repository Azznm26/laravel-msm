<?php

namespace App\Support;

/**
 * Kalkulator Profile Matching standar:
 * GAP = Nilai Aktual - Nilai Target (skala 1-5)
 *
 * Tabel bobot GAP standar (literatur Profile Matching Kusrini):
 *  gap  0 -> 5.0   gap  1 -> 4.5   gap -1 -> 4.0
 *  gap  2 -> 3.5   gap -2 -> 3.0
 *  gap  3 -> 2.5   gap -3 -> 2.0
 *  gap  4 -> 1.5   gap -4 -> 1.0
 *
 * Formula umum: 5 - |gap| + (0.5 jika gap > 0, selain itu 0), floor di 1.0
 */
class ProfileMatchingCalculator
{
    public static function gapToWeight(int $gap): float
    {
        $weight = 5 - abs($gap) + ($gap > 0 ? 0.5 : 0);

        return max(1.0, $weight);
    }

    /**
     * @param  \Illuminate\Support\Collection<int, array{criteria: \App\Models\CareerGapCriteria, target: int, actual: int}>  $rows
     *         Baris per kriteria dalam SATU aspek (sudah difilter aspect_id yang sama)
     * @return array{ncf: float, nsf: float, aspect_score: float, details: array}
     */
    public static function calculateAspect($rows, float $cfWeight, float $sfWeight): array
    {
        $coreWeights = [];
        $secondaryWeights = [];
        $details = [];

        foreach ($rows as $row) {
            $gap = $row['actual'] - $row['target'];
            $weight = self::gapToWeight($gap);

            if ($row['criteria']->isCore()) {
                $coreWeights[] = $weight;
            } else {
                $secondaryWeights[] = $weight;
            }

            $details[] = [
                'criteria' => $row['criteria']->name,
                'factor_type' => $row['criteria']->factor_type,
                'target' => $row['target'],
                'actual' => $row['actual'],
                'gap' => $gap,
                'weight' => $weight,
            ];
        }

        $ncf = count($coreWeights) > 0 ? array_sum($coreWeights) / count($coreWeights) : 0;
        $nsf = count($secondaryWeights) > 0 ? array_sum($secondaryWeights) / count($secondaryWeights) : 0;

        // Kalau salah satu faktor tidak ada kriterianya, pakai faktor yang ada saja (bobot 100%)
        if (count($coreWeights) === 0 && count($secondaryWeights) > 0) {
            $aspectScore = $nsf;
        } elseif (count($secondaryWeights) === 0 && count($coreWeights) > 0) {
            $aspectScore = $ncf;
        } else {
            $aspectScore = ($ncf * ($cfWeight / 100)) + ($nsf * ($sfWeight / 100));
        }

        return [
            'ncf' => round($ncf, 2),
            'nsf' => round($nsf, 2),
            'aspect_score' => round($aspectScore, 2),
            'details' => $details,
        ];
    }
}
