<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class AmountExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('amount', [$this, 'amount']),
        ];
    }

    public function amount($value, string $symbol = '€', string $decsep = ',', string $thousandsep = ' ')
    {
        $finalValue = $value / 100;
        $finalValue = number_format($finalValue, 2, $decsep, $thousandsep);

        return $finalValue.''.$symbol;
    }
}
