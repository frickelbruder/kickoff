<?php
namespace Frickelbruder\KickOff\Tests\Log;

use Frickelbruder\KickOff\Log\Listener\Listener;
use Frickelbruder\KickOff\Rules\Contracts\RuleInterface;

class LogListenerStub implements Listener {

    public $logs = array();

    public $finishCalled = false;

    public function log($sectionName, $targetUrl, RuleInterface $rule, $success) {
        $ruleName = $rule->getName();
        if(!isset($this->logs[$sectionName])) {
            $this->logs[$sectionName] = array();
        }
        if(!isset($this->logs[$sectionName][$ruleName])) {
            $this->logs[$sectionName][$ruleName] = array();
        }
        $this->logs[$sectionName][$ruleName] = $success;
    }

    public function finish() {
        $this->finishCalled = true;
    }


}
