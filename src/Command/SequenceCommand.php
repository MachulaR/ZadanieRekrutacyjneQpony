<?php

namespace App\Command;

use App\Service\SequenceService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class SequenceCommand extends Command {

    protected static $defaultName = 'app:sequence';

    /**
     * @var SequenceService
     */
    private $sequenceService;

    /**
     * SequenceCommand constructor.
     * @param $sequenceService
     */
    public function __construct(SequenceService $sequenceService) {
        $this->sequenceService = $sequenceService;
        parent::__construct();
    }

    protected function configure() {
        $this
            ->setDescription('Oblicza największą liczbę ciągu w podanym zakresie Przyjmuje do 10 liczb jako parametry, bądź wymaga wpisania ich w konsoli')
            ->addArgument('number1', InputArgument::OPTIONAL)
            ->addArgument('number2', InputArgument::OPTIONAL)
            ->addArgument('number3', InputArgument::OPTIONAL)
            ->addArgument('number4', InputArgument::OPTIONAL)
            ->addArgument('number5', InputArgument::OPTIONAL)
            ->addArgument('number6', InputArgument::OPTIONAL)
            ->addArgument('number7', InputArgument::OPTIONAL)
            ->addArgument('number8', InputArgument::OPTIONAL)
            ->addArgument('number9', InputArgument::OPTIONAL)
            ->addArgument('number10', InputArgument::OPTIONAL)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $text = '';
        if ($input->getArgument('number1')) {
            foreach ($input->getArguments() as $argument) {
                if ($argument != self::getDefaultName()) {
                    $text = $text . $argument . "\r\n";
                }
            }
        } else {
            $handle = fopen("php://stdin", "r");
            $output->writeln('Brak danych. Podaj liczbę, po czym naciśnij ENTER. Aby zakończyć wpisywanie wpisz w nowej linii :q');

            while (true) {
                $line = fgets($handle);
                if ($line == ":q\r\n") {
                    break;
                }
                $text = $text . $line;
            }
        }

        try {
            $array = $this->sequenceService->prepareInput($text);
        } catch (\Exception $e) {
            $output->writeln('ERROR: ' . $e->getMessage());
            $output->writeln("Shutdown...");
            return 0;
        }

        $sequence = $this->sequenceService->sequenceOutput($array);

        foreach ($array as $number) {
            $var = $this->sequenceService->findBiggestNumberInSequenceOutput($number, $sequence);
            $output->writeln('Input: '.$number.' Output: '.$var);
        }
        return 0;
    }
}