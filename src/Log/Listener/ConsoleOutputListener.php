<?php
namespace Frickelbruder\KickOff\Log\Listener;

use Frickelbruder\KickOff\Rules\Rule;
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


    public function log($sectionName, $targetUrl, Rule $rule, $success) {
        $output = '.';
        if(!$success) {
            $this->messages[] = str_replace(array('%URL%', '%SECTION%'), array($targetUrl, $sectionName), $rule->getErrorMessage());
            $output = '<error>F</error>';
            $this->counter['errors']++;
        } else {
            $this->counter['success']++;
        }
        $this->consoleOutput->write($output);
    }

    public function finish() {
        $this->consoleOutput->writeln("");
        foreach($this->messages as $message) {
            $this->consoleOutput->writeln($message);
        }
        $this->consoleOutput->writeln("");

        $totalTests = $this->counter['success'] + $this->counter['errors'];
        if($this->counter['errors'] == 0) {
            $this->consoleOutput->writeln("Ok ($totalTests Tests without errors)");
        } else {

            $this->consoleOutput->writeln("Fail ($totalTests Tests " . $this->counter['success'] . ' passed, ' . $this->counter['errors'] . ' failed)');
        }
    }

}