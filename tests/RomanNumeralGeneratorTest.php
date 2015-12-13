<?php

namespace RomanNumerals\Testing;

use RomanNumerals\RomanNumeralGenerator;

class RomanNumeralGeneratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \RomanNumerals\RomanNumeralGenerator
     */
    private $generator;

    public function setUp()
    {
        $this->generator = new RomanNumeralGenerator();
    }

    /**
     * @dataProvider numeralsDataProvider
     * @param $arabicNumber
     * @param $romanNumber
     */
    public function testCanGenerate($arabicNumber, $romanNumber)
    {
        $this->assertEquals($romanNumber, $this->generator->generate($arabicNumber));
    }

    /**
     * @dataProvider numeralsDataProvider
     * @param $arabicNumber
     * @param $romanNumber
     */
    public function testCanParse($arabicNumber, $romanNumber)
    {
        $this->assertEquals((int)$arabicNumber, $this->generator->parse($romanNumber));
    }

    /**
     * @dataProvider generateThrowsSupportedNumbersException_DataProvider
     * @param $romanNumber
     */

    public function testGenerateThrowsSupportedNumbersException($romanNumber)
    {
        $this->setExpectedException('InvalidArgumentException', 'Numbers from 1 to 3999 only supported.');
        $this->generator->generate($romanNumber);
    }

    public function testParseThrowsIncorrectSymbolExcenption()
    {
        $this->setExpectedException('InvalidArgumentException', "XXG has symbol that doesn't exists in roman numerals.");
        $this->generator->parse('XXG');
    }

    /**
     * @dataProvider parseThrowsDisallowedRepetitionException_DataProvider
     * @param $romanNumber
     */
    public function testParseThrowsDisallowedRepetitionException($romanNumber)
    {
        $this->setExpectedException('InvalidArgumentException', "$romanNumber doesn't seem to be correct roman numeral (incorrect symbol repetitions).");
        $this->generator->parse($romanNumber);
    }

    /**
     * @dataProvider parseThrowsTooSmallNumberGoesBeforeLargeOneException_DataProvider
     * @param $romanNumber
     */
    public function testParseThrowsTooSmallNumberGoesBeforeLargeOneException($romanNumber)
    {
        $this->setExpectedException('InvalidArgumentException', "$romanNumber doesn't seem to be correct roman numeral (too small number goes before larger one).");
        $this->generator->parse($romanNumber);
    }

    /**
     * @dataProvider canThrowIsNotANumberException_DataProvider
     * @param $arabicNumber
     */
    public function testCanThrowIsNotANumberException($arabicNumber)
    {
        $this->setExpectedException('InvalidArgumentException', "$arabicNumber is not a number.");
        $this->generator->generate($arabicNumber);
    }

    public function numeralsDataProvider()
    {
        return json_decode(file_get_contents(__DIR__.'/roman-numerals-list.json'), true);
    }

    public function generateThrowsSupportedNumbersException_DataProvider()
    {
        return [
            [0, -5, 4000]
        ];
    }

    public function parseThrowsDisallowedRepetitionException_DataProvider()
    {
        return [
            ['IIII', 'XXXXXXX', 'CCCC', 'MMMMMM', 'VV', 'LLL', 'DD']
        ];
    }

    public function parseThrowsTooSmallNumberGoesBeforeLargeOneException_DataProvider()
    {
        return [
            ['IM'], ['ID'], ['IC'], ['IL'], ['VM'], ['VD'], ['VC'], ['XM'], ['XD'], ['LM']
        ];
    }

    public function canThrowIsNotANumberException_DataProvider()
    {
        return [
            ['AA', 'Aa', '1a', 'aa9']
        ];
    }
}
