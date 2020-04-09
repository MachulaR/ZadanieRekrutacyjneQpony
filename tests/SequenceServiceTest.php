<?php

namespace App\Tests;

use App\Exception\InputException;
use App\Exception\OutOfBoundException;
use App\Service\SequenceService;
use PHPUnit\Exception;
use PHPUnit\Framework\TestCase;

class SequenceServiceTest extends TestCase {

    /**
     * @dataProvider provider_testPrepareInput__WhenDataIsCorrect
     * @param $string
     */
    public function testPrepareInput__WhenDataIsCorrect($string){
        $sequenceService = new SequenceService();

        try {
            $sequenceService->prepareInput($string);
        } catch (Exception $e) {
            $this->fail();
        }
        $this->assertTrue(true);
    }

    /**
     * @dataProvider provider_testCheckInput_ThrowsInputException_WhenDataHasIllegalChars
     * @param $string
     */
    public function testCheckInput_ThrowsInputException_WhenDataHasIllegalChars($string){
        $this->expectException(InputException::class);

        $sequenceService = new SequenceService();
        $sequenceService->prepareInput($string);
    }

    public function testCheckInput_ThrowsInputException_WhenDataHasNumberBiggerThanMAX(){
        $this->expectException(OutOfBoundException::class);

        $testString = SequenceService::MAX_VALUE + 1;

        $sequenceService = new SequenceService();
        $sequenceService->prepareInput($testString);
    }

    public function testCheckInput_ThrowsInputException_WhenNumberIsNegative(){
        $this->expectException(InputException::class);

        $testString = '-1';

        $sequenceService = new SequenceService();
        $sequenceService->prepareInput($testString);
    }

    public function testCheckInput_ThrowsInputException_WhenDataHasTooManyQueries(){
        $this->expectException(InputException::class);

        $testString = '';
        for ($i=0; $i<11 ; $i++) {
            $testString = $testString . $i . "\r\n";
        }
        $sequenceService = new SequenceService();
        $sequenceService->prepareInput($testString);
    }

    /**
     * @dataProvider provider_testCheckInput_ThrowsInputException_WhenNumbersAreSplitIncorrectly
     * @param $string
     */
    public function testCheckInput_ThrowsInputException_WhenNumbersAreSplitIncorrectly($string){
        $this->expectException(InputException::class);

        $sequenceService = new SequenceService();
        $sequenceService->prepareInput($string);
    }

    /**
     * @dataProvider provider_testCheckInput_ThrowsInputException_WhenNumberIsDecimal
     * @param $number
     */
    public function testCheckInput_ThrowsInputException_WhenNumberIsDecimal($number){
        $this->expectException(InputException::class);
        $sequenceService = new SequenceService();
        $sequenceService->prepareInput($number);
    }

    /**
     * @dataProvider provider_testPrepareInput_ThrowsInputException_WhenStringIsEmpty
     * @param $string
     */
    public function testPrepareInput_ThrowsInputException_WhenStringIsEmpty($string){
        $this->expectException(InputException::class);

        $sequenceService = new SequenceService();
        $sequenceService->prepareInput($string);
    }

    public function testPrepareInput_ThrowsInputException_WhenStringIsSpace(){
        $this->expectException(InputException::class);

        $testString = ' ';

        $sequenceService = new SequenceService();
        $sequenceService->prepareInput($testString);
    }

    public function testPrepareInput_ThrowsInputException_WhenStringIsEnter(){
        $this->expectException(InputException::class);

        $testString = "\r\n";

        $sequenceService = new SequenceService();
        $sequenceService->prepareInput($testString);
    }

    public function provider_testPrepareInput__WhenDataIsCorrect()
    {
        return [
            ['1'],
            ['0'],
            ['5'],
            ['10'],
            ["5 \r\n10"],
            ["5 \r\n 10"],
            ["5 \r\n\r\n 10"],
            ["\r\n 4\r\n3"],
            ["3\r\n 3   \r\n\r\n"],
        ];
    }

    public function provider_testCheckInput_ThrowsInputException_WhenDataHasIllegalChars()
    {
        return [
            ['string'],
            ['1k1'],
            ['1 1'],
            ['1adsa'],
            ['zdf1'],
            ['1!@#%^'],
            ['1%'],
            ['4/1'],
            ['1+1'],
        ];
    }

    public function provider_testCheckInput_ThrowsInputException_WhenNumbersAreSplitIncorrectly()
    {
        return [
            ['1,'. "\r\n" . '2'],
            ['1 3'],
            ['4, 5'],
            ['1, '. "\r\n" . '2'],
        ];
    }

    public function provider_testCheckInput_ThrowsInputException_WhenNumberIsDecimal()
    {
        return [
            ['1,0'],
            ['1,3'],
            ['1.4'],
            ['2.0'],
        ];
    }

    public function provider_testPrepareInput_ThrowsInputException_WhenStringIsEmpty()
    {
        return [
            [''],
            [' '],
            ['     '],
            ["\r\n"],
            ["\r\n "],
            ["\r\n   "],
            ["  \r\n  "],
            ["  \r\n"],
        ];
    }


}