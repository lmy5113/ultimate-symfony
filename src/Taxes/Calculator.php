<?php

namespace App\Taxes;

class Calculator {

    public function __construct(float $tva)
    {
    }

    /**
     * caculate vat
     */
    public function calculate(float $price): float {
        return $price * (20 / 100);
    }
}