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

    public function amount($value, string $symbol = '€', string $decsep = ',', $thousandsep = ' ')
    {
        return number_format($value / 100, 2, $decsep, $thousandsep) . " {$symbol}";
    }
}
