<?php
namespace Frickelbruder\KickOff\Log;

use Frickelbruder\KickOff\Log\Listener\Listener;


class Logger {

    /**
     * @var Listener
     */
    private $listeners = array();

    /**
     * @param Listener $listener
     */
    public function addListener(Listener $listener) {
        $this->listeners[] = $listener;
    }

    /**
     * @param string $sectionName
     * @param string $targetUrl
     * @param string $ruleName
     */
    public function logSuccess($sectionName, $targetUrl, $ruleName) {
        $this->log($sectionName, $targetUrl, $ruleName, true);
    }

    /**
     * @param string $sectionName
     * @param string $targetUrl
     * @param string $ruleName
     */
    public function logFail($sectionName, $targetUrl, $ruleName) {
        $this->log($sectionName, $targetUrl, $ruleName, false);
    }

    /**
     * @param string $sectionName
     * @param string $targetUrl
     * @param string $ruleName
     * @param Boolean $success
     */
    public function log($sectionName, $targetUrl, $ruleName, $success) {
        foreach($this->listeners as $listener) {
            $listener->log($sectionName, $targetUrl, $ruleName, $success);
        }
    }

    public function finish() {
        foreach($this->listeners as $listener) {
            $listener->finish();
        }
    }
}