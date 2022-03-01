<?php
namespace App\Taxes;

class Detector {

    protected $limit;
    public function __construct(float $limit)
    {
        $this->limit = $limit;
    }

    /**
     * caculate vat
     */
    public function detect(float $price): bool {
        return $price > $this->limit;
    }
}