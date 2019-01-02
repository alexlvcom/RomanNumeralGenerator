<?php

namespace RomanNumerals\Tests\Libs;

use RomanNumerals\Libs\RomanNumeralGenerator;
use PHPUnit\Framework\TestCase;
use InvalidArgumentException;

class RomanNumeralGeneratorTest extends TestCase
{
    /**
     * @var RomanNumeralGenerator
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
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Numbers from 1 to 3999 only supported.');
        
        $this->generator->generate($romanNumber);
    }

    public function testParseThrowsIncorrectSymbolExcenption()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('XXG has symbol that doesn\'t exists in roman numerals.');
        $this->generator->parse('XXG');
    }

    /**
     * @dataProvider parseThrowsDisallowedRepetitionException_DataProvider
     * @param $romanNumber
     */
    public function testParseThrowsDisallowedRepetitionException($romanNumber)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("$romanNumber doesn't seem to be correct roman numeral (incorrect symbol repetitions).");
        
        $this->generator->parse($romanNumber);
    }

    /**
     * @dataProvider parseThrowsTooSmallNumberGoesBeforeLargeOneException_DataProvider
     * @param $romanNumber
     */
    public function testParseThrowsTooSmallNumberGoesBeforeLargeOneException($romanNumber)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("$romanNumber doesn't seem to be correct roman numeral (too small number goes before larger one).");
        
        $this->generator->parse($romanNumber);
    }

    /**
     * @dataProvider canThrowIsNotANumberException_DataProvider
     * @param $arabicNumber
     */
    public function testCanThrowIsNotANumberException($arabicNumber)
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("$arabicNumber is not a number.");
        
        $this->generator->generate($arabicNumber);
    }

    public function numeralsDataProvider()
    {
        return json_decode(file_get_contents(__DIR__.'/../stubs/roman-numerals-list.json'), true);
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
