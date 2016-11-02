<?php
namespace Frickelbruder\KickOff\Log\Listener;

use Frickelbruder\KickOff\Rules\Interfaces\RuleInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\ConsoleOutputInterface;

class ConsoleOutputListener implements Listener {

    /**
     * @var ConsoleOutputInterface
     */
    private $consoleOutput = null;

    private $messages = array();

    private $counter = array('success' => 0, 'errors' => 0);

    private $padOutputToLongestMessage = true;

    private $outputPadLength = 0;
    private $outputPadMinLength = 80;

    /**
     * @param ConsoleOutputInterface $consoleOutput
     */
    public function __construct(ConsoleOutputInterface $consoleOutput = null) {
        if(is_null($consoleOutput)) {
            $consoleOutput = new ConsoleOutput();
        }
        $this->consoleOutput = $consoleOutput;
        $this->outputPadLength = $this->outputPadMinLength;
    }

    public function log($sectionName, $targetUrl, RuleInterface $rule, $success) {
        $output = '.';
        $counterToUpdate = 'success';
        if(!$success) {
            $message = 'Rule "' . $rule->getName() . '": ' . $rule->getErrorMessage();
            $this->updatePadLength($message);
            $this->messages[ucwords($sectionName)][] = $message;
            $output = '<error>F</error>';
            $counterToUpdate = 'errors';
        }
        $this->counter[$counterToUpdate]++;
        $this->consoleOutput->write($output);
    }

    public function finish() {
        $this->consoleOutput->writeln("");

        $this->writeErrors();

        $this->consoleOutput->writeln('');

        $totalTests = $this->counter['success'] + $this->counter['errors'];

        $message = "Ok ($totalTests Tests without errors)";
        if($this->counter['errors'] > 0) {
            $message = "Fail ($totalTests Tests " . $this->counter['success'] . ' passed, ' . $this->counter['errors'] . ' failed)';
        }
        $this->consoleOutput->writeln($message);
    }

    private function updatePadLength($message='') {
        if ($this->padOutputToLongestMessage && (strlen($message)>$this->outputPadLength)) {
            $this->outputPadLength = strlen($message)+6;
        }
    }

    private function writeErrors() {
        if(count($this->messages) == 0) {
            return;
        }
        $this->consoleOutput->writeln("");
        foreach( $this->messages as $section => $messages ) {
            $this->writeErrorMessageLine('');
            $this->writeErrorMessageLine( '  ' . $section . '  ');
            $this->writeMessages( $messages );
        }
        $this->writeErrorMessageLine('');
    }


    /**
     * @param $message
     */
    private function writeErrorMessageLine($message = '') {
        $this->consoleOutput->writeln( '<error>' . str_pad($message , $this->outputPadLength ) . '</error>' );
    }

    /**
     * @param $messages
     */
    private function writeMessages($messages) {
        foreach( $messages as $message ) {
            $this->writeErrorMessageLine( '    ' . $message . '  ' );
        }
    }


}