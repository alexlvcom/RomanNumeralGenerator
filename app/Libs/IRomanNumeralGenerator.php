<?php

namespace RomanNumerals\Libs;

interface IRomanNumeralGenerator
{

    /**
     * Converts arabic number to Roman numeral
     *
     * @param int $arabicNumber
     * @return string
     */
    public function generate($arabicNumber);

    /**
     * Converts Roman numeral to Arabic number
     *
     * @param string $romanNumber
     * @return int
     */
    public function parse($romanNumber);
}
