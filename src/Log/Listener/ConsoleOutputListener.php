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

    public function __construct() {
        $this->consoleOutput = new ConsoleOutput();
    }


    public function log($sectionName, $targetUrl, RuleInterface $rule, $success) {
        $output = '.';
        $counterToUpdate = 'success';
        if(!$success) {
            $this->messages[] =  $rule->getErrorMessage() . '(' . $sectionName. ':' . $rule->getName() . ')';
            $output = '<error>F</error>';
            $counterToUpdate = 'errors';
        }
        $this->counter[$counterToUpdate]++;
        $this->consoleOutput->write($output);
    }

    public function finish() {
        $this->consoleOutput->writeln("");
        foreach($this->messages as $message) {
            $this->consoleOutput->writeln('<error>'.$message.'</error>');
        }
        $this->consoleOutput->writeln("");

        $totalTests = $this->counter['success'] + $this->counter['errors'];

        $message = "Ok ($totalTests Tests without errors)";
        if($this->counter['errors'] > 0) {
            $message = "Fail ($totalTests Tests " . $this->counter['success'] . ' passed, ' . $this->counter['errors'] . ' failed)';
        }
        $this->consoleOutput->writeln($message);
    }

}