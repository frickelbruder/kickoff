<?php
namespace Frickelbruder\KickOff\Log\Listener;

use Frickelbruder\KickOff\Rules\RuleInterface;

class CsvLogListener implements Listener {

    public $logFileName = 'kickoff.csv';

    private $logs = array();

    public function log($sectionName, $targetUrl, RuleInterface $rule, $success) {
        $this->logs[] = array($sectionName, $targetUrl, $rule->getName(), $success ? 'Ok' : 'FAILED');
    }


    public function finish() {
        $fp = fopen($this->logFileName, 'w');
        foreach($this->logs as $log) {
            fputcsv( $fp, $log);
        }
        fclose($fp);

    }



}