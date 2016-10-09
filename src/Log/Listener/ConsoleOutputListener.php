<?php
namespace Frickelbruder\KickOff\Log\Listener;

use Frickelbruder\KickOff\Rules\RuleInterface;
use Symfony\Component\Console\Output\ConsoleOutput;

class ConsoleOutputListener implements Listener {

    /**
     * @var ConsoleOutput
     */
    private $consoleOutput = null;

    private $messages = array();

    private $counter = array('success' => 0, 'errors' => 0);

    private $outputPadLength = 0;
    private $outputPadMinLength = 80;
    private $padOutputToLongestMessage = true;

    public function __construct() {
        $this->consoleOutput = new ConsoleOutput();
        $this->outputPadLength = $this->outputPadMinLength;
    }


    public function log($sectionName, $targetUrl, RuleInterface $rule, $success) {
        $output = '.';
        $counterToUpdate = 'success';
        if(!$success) {
            $message = $rule->getReadableName() . ': ' . $rule->getErrorMessage();
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
        foreach($this->messages as $section=>$messages) {
            $this->consoleOutput->writeln('<error>'.str_pad('', $this->outputPadLength).'</error>');
            $this->consoleOutput->writeln('<error>'.str_pad('  '.$section.'  ', $this->outputPadLength).'</error>');
            foreach ($messages as $message) {
                $this->consoleOutput->writeln('<error>'.str_pad('    '.$message.'  ', $this->outputPadLength).'</error>');
            }
        }
        $this->consoleOutput->writeln('<error>'.str_pad('', $this->outputPadLength).'</error>');
        $this->consoleOutput->writeln("");

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
}
