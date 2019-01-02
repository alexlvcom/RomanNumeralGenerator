<?php

namespace RomanNumerals\Libs;

use \InvalidArgumentException;

class RomanNumeralGenerator implements IRomanNumeralGenerator
{
    private $mapArabic = [1, 5, 10, 50, 100, 500, 1000];
    private $mapRoman = ['I', 'V', 'X', 'L', 'C', 'D', 'M'];

    /**
     * Converts arabic number to Roman numeral
     *
     * @param int $arabicNumber
     * @return string
     */
    public function generate($arabicNumber)
    {
        if (!is_numeric($arabicNumber)) {
            throw new InvalidArgumentException("$arabicNumber is not a number.");
        }

        if (((int)$arabicNumber > 0 && (int)$arabicNumber <= 3999) === false) {
            throw new InvalidArgumentException('Numbers from 1 to 3999 only supported.');
        }

        /**
         * 1) Splitting the number to thousands, hundreds, dozens and units
         * 2) Converting each digit to Roman numeral individually and then just concatenate the result.
         */

        $result = '';

        while ($arabicNumber > 0) {
            $split = str_split($arabicNumber);
            $digit = (int)$split[0];

            $n = (int)'1'.str_repeat('0', count($split) - 1); // making thousand/hundred/dozen/unit considering digit position in the number
            $result .= $this->makeRoman($digit, $this->mapRoman[$this->getArabicOffset($n)]);
            $arabicNumber -= $digit * $n;

            /**
             * The same algorithm in simpler (to understand) form:
             *
             * switch (count($split)) {
             * case 1:
             * $result .= $this->makeRoman($digit, 'I');
             * $arabicNumber -= $digit;
             * break;
             * case 2:
             * $result .= $this->makeRoman($digit, 'X');
             * $arabicNumber -= $digit * 10;
             * break;
             * case 3:
             * $result .= $this->makeRoman($digit, 'C');
             * $arabicNumber -= $digit * 100;
             * break;
             * case 4:
             * $result .= $this->makeRoman($digit, 'M');
             * $arabicNumber -= $digit * 1000;
             * break;
             * }
             */
        }


        return $result;
    }


    /**
     * Convert single digit (1-9) to Roman numeral
     *
     * @param $digit
     * @param $symbol
     * @return string
     */
    private function makeRoman($digit, $symbol)
    {
        $result = '';
        $offset = $this->getRomanOffset($symbol);

        if ($digit < 4) {
            $result = str_repeat($symbol, $digit);
        } elseif ($digit === 4) {
            $result = $symbol.$this->mapRoman[$offset + 1];
        } elseif ($digit === 5) {
            $result = $this->mapRoman[$offset + 1];
        } elseif ($digit < 9) {
            $result = $this->mapRoman[$offset + 1].str_repeat($symbol, $digit - 5);
        } elseif ($digit === 9) {
            $result = $symbol.$this->mapRoman[$offset + 2];
        }

        return $result;

    }

    /**
     * Converts Roman numeral to Arabic number
     *
     * @param string $romanNumber
     * @return int
     */
    public function parse($romanNumber)
    {
        // check whether string has symbols that are not used in roman numerals
        if (!preg_match('/^['.implode($this->mapRoman).']+$/', $romanNumber)) {
            throw new InvalidArgumentException("$romanNumber has symbol that doesn't exists in roman numerals.");
        }

        // check whether symbols doesn't exceed their allowed repetitions
        if (preg_match('/(I|X|C|M)\\1{3}/', $romanNumber) || preg_match('/(V|L|D)\\1/', $romanNumber)) {
            throw new InvalidArgumentException("$romanNumber doesn't seem to be correct roman numeral (incorrect symbol repetitions).");
        }

        // when symbol with larger value is preceded by symbol with smaller value, check whether symbol with smaller value is not more than 1/10th the larger one.
        if (preg_match('/(IM|ID|IC|IL|VM|VD|VC|XM|XD|LM)/', $romanNumber)) {
            throw new InvalidArgumentException("$romanNumber doesn't seem to be correct roman numeral (too small number goes before larger one).");
        }

        /**
         * 1) if current symbol value equals to or larger than than next symbol value, then addition rule should be applyed (current symbol value + next symbol value)
         * 2) if current symbol value is smaller than next symbol value, then current symbol value is substracted from next symbol value
         */
        $result = 0;

        while (trim($romanNumber) !== '') {
            $split      = str_split($romanNumber);
            $offset     = $this->getRomanOffset($split[0]);
            $offsetNext = array_key_exists(1, $split) ? $this->getRomanOffset($split[1]) : false;

            if (($offsetNext !== false && $offset >= $offsetNext) || $offsetNext === false) {
                $result += $this->mapArabic[$offset];
                $romanNumber = substr($romanNumber, 1);
            } elseif ($offsetNext !== false && $offset < $offsetNext) {
                $result += $this->mapArabic[$offsetNext] - $this->mapArabic[$offset];
                $romanNumber = substr($romanNumber, 2);
            }
        }

        return $result;
    }

    private function getRomanOffset($symbol)
    {
        return array_search($symbol, array_values($this->mapRoman));
    }

    private function getArabicOffset($number)
    {
        return array_search($number, array_values($this->mapArabic));
    }
}
