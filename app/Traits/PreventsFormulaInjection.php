<?php

namespace App\Traits;

trait PreventsFormulaInjection
{
    protected function safeCell(mixed $value): mixed
    {
        if ($value === null || $value === '') {
            return $value;
        }

        $string = (string) $value;

        if ($string === '-') {
            return $string;
        }

        $first = $string[0];
        if (in_array($first, ['=', '+', '-', '@', "\t", "\r"], true)) {
            return "'" . $string;
        }

        return $value;
    }
}
