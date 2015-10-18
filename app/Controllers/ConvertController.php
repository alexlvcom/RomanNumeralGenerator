<?php

namespace RomanNumerals;

use \Exception;

class ConvertController extends Controller implements IRomanNumeralGenerator
{
    public function generate($arabicNumber)
    {
        try {
            $romanNumber = $this->getRomanNumeralGenerator()->generate($arabicNumber);
            $this->response(['data' => $romanNumber]);
        } catch (Exception $e) {
            $this->response(['error' => $e->getMessage()]);
        }

    }

    public function parse($romanNumber)
    {
        try {
            $arabicNumber = $this->getRomanNumeralGenerator()->parse($romanNumber);
            $this->response(['data' => $arabicNumber]);
        } catch (Exception $e) {
            $this->response(['error' => $e->getMessage()]);
        }
    }
}
