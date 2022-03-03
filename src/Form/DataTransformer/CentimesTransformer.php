<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class CentimesTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        if (is_null($value)) {
            return;
        }

        return $value / 100;
    }

    public function reverseTransform($value)
    {
        if (is_null($value)) {
            return;
        }

        return $value * 100;
    }
}
