<?php

namespace App\Services;

class CocomoService
{
    public static function validate(float $kloc, float $salary, string $mode, array $ratings): array
    {
        $errors = [];
        if ($kloc <= 0)   $errors[] = 'KLOC debe ser mayor que 0';
        if ($salary <= 0) $errors[] = 'El salario debe ser mayor que 0';
        if (!array_key_exists($mode, config('cocomo.modes'))) {
            $errors[] = 'Modo de proyecto inválido';
        }

        $drivers = config('cocomo.drivers');
        if (!$drivers) {
            $errors[] = 'Configuración de COCOMO no encontrada.';
            return $errors;
        }

        foreach ($drivers as $key => $table) {
            $rating = $ratings[$key] ?? 'Nominal';
            if (!isset($table[$rating]) || $table[$rating] === null) {
                $errors[] = "Valoración inválida o N/A para $key";
            }
        }

        return $errors;
    }

    public static function computeEAF(array $ratings): array
    {
        $drivers = config('cocomo.drivers');
        $eaf = 1.0;
        $detail = [];

        foreach ($drivers as $key => $table) {
            $rating = $ratings[$key] ?? 'Nominal';
            $mult = $table[$rating] ?? 1.0;
            $mult = $mult ?: 1.0;
            $eaf *= $mult;
            $detail[$key] = ['rating' => $rating, 'mult' => $mult];
        }

        return [$eaf, $detail];
    }

    public static function calculate(float $kloc, float $salary, string $mode, array $ratings): array
    {
        [$eaf, $detail] = self::computeEAF($ratings);
        $params = config("cocomo.modes.$mode");

        $pm   = $params['a'] * pow($kloc, $params['b']) * $eaf;
        $tdev = $params['c'] * pow($pm, $params['d']);
        $p    = $pm / $tdev;
        $c    = $p * $salary * $tdev; // equivalente a PM × salario

        return [
            'pm' => $pm,
            'tdev' => $tdev,
            'p' => $p,
            'c' => $c,
            'eaf' => $eaf,
            'detail' => $detail,
        ];
    }
}
