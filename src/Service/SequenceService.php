<?php

namespace App\Service;

use App\Exception\InputException;
use App\Exception\OutOfBoundException;
use Exception;

class SequenceService
{
    const MAX_VALUE = 99999;
    const MAX_NUMBER_QUERIES = 10;

    /**
     * @param string||null $numericalSequence
     * @return array
     * @throws Exception
     */
    public function prepareInput($numericalSequence) {
        $numericalSequence = trim($numericalSequence);
        if (!strlen($numericalSequence)) {
            throw new InputException('Niewłaściwe dane wejściowe. Sprawdź je i spróbuj ponownie');
        }
        $array = explode("\r\n", $numericalSequence);
        $array = $this->checkInput($array);
        return $array;
    }

    /**
     * @param array $array
     * @return array
     */
    public function sequenceOutput(array $array) {
        $number = max($array);
        $output = [
            0 => 0,
            1 => 1,
        ];

        for ($i = 2; $i<=$number; $i++) {
            if($i % 2) {
                $output[] = $output[$i/2] + $output[$i/2 + 1];
            } else {
                $output[] = $output[$i/2];
            }
        }

        return $output;
    }

    /**
     * @param int $number
     * @param array $sequenceOutput
     * @return mixed
     */
    public function findBiggestNumberInSequenceOutput(int $number, array $sequenceOutput) { //todo sprawdzic warunki?
        $array = array_slice($sequenceOutput, 0, $number+1);
        return max($array);
    }

    /**
     * @param array $array
     * @return array
     * @throws Exception
     */
    private function checkInput(array $array) {
        $returnArray = [];
        foreach($array as $value){
            $value = trim($value);
            if(!preg_match('/^[0-9]*$/', $value)) {
                throw new InputException('Niewłaściwe dane wejściowe. Sprawdź je i spróbuj ponownie');
            }
            if($value > self::MAX_VALUE) {
                throw new OutOfBoundException('Przynajmniej jedna z liczb wykracza po za zakres: 0-99999');
            }
            if ($value == 0 || !$value == '' || !$value == null) {
                $returnArray[] = $value;
            }
        }

        if (sizeof($returnArray) > self::MAX_NUMBER_QUERIES) {
            throw new InputException('Za dużo liczb. Maxymalna ilość liczb dopuszczona na raz, to: '. self::MAX_NUMBER_QUERIES);
        }

        return $returnArray;
    }
}