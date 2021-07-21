<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AmountExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('amount', [$this, 'amount'])
        ];
    }

    public function amount(int $value, string $symbol = '€', string $decsep = ',', string $thousandSep = " ")
    {
        $finalValue = $value / 100;
        //valeur, nbre de décimale, séparateur décimale, séparateur millier
        $finalValue = number_format($finalValue, 2, $decsep, $thousandSep);
        $finalValue .= $symbol;
        return $finalValue;
    }
}
