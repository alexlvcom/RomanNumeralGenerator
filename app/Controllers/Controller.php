<?php

namespace RomanNumerals\Controllers;

use RomanNumerals\Libs\IRomanNumeralGenerator;

class Controller implements IController
{
    public function __construct()
    {

    }

    public function index()
    {

    }

    /**
     * @var IRomanNumeralGenerator
     */
    private $romanNumeralGenerator;

    /**
     * @return IRomanNumeralGenerator
     */
    public function getRomanNumeralGenerator()
    {
        return $this->romanNumeralGenerator;
    }

    /**
     * @param IRomanNumeralGenerator $romanNumeralGenerator
     */
    public function setRomanNumeralGenerator(IRomanNumeralGenerator $romanNumeralGenerator)
    {
        $this->romanNumeralGenerator = $romanNumeralGenerator;
    }

    /**
     * @param $data
     * @param int $httpStatusCode
     */
    public function response($data, $httpStatusCode = null)
    {
        $dataDefault = [
            'error' => false,
            'data'  => null
        ];

        $data = array_merge($dataDefault, $data);

        header('Content-Type: application/json');

        if ($data['error'] !== false && $httpStatusCode === null) {
            $httpStatusCode = 500;
        }

        switch ($httpStatusCode) {
            case 404:
                header('HTTP/1.1 404 Not Found', true, $httpStatusCode);
                break;
            case 405:
                header('HTTP/1.1 405 Method Not Allowed', true, $httpStatusCode);
                break;
            case 500:
                header('HTTP/1.1 500 Internal Server Error', true, $httpStatusCode);
                break;
        }

        echo json_encode($data);
    }
}
