<?php
namespace Frickelbruder\KickOff\Log;

use Frickelbruder\KickOff\Log\Listener\Listener;
use Frickelbruder\KickOff\Rules\Rule;


class Logger {

    /**
     * @var Listener[]
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
     * @param Rule $rule
     *
     */
    public function logSuccess($sectionName, $targetUrl, Rule $rule) {
        $this->log($sectionName, $targetUrl, $rule, true);
    }

    /**
     * @param string $sectionName
     * @param string $targetUrl
     * @param Rule $rule
     */
    public function logFail($sectionName, $targetUrl, Rule $rule) {
        $this->log($sectionName, $targetUrl, $rule, false);
    }

    /**
     * @param string $sectionName
     * @param string $targetUrl
     * @param Rule $rule
     * @param Boolean $success
     */
    public function log($sectionName, $targetUrl, Rule $rule, $success) {
        foreach($this->listeners as $listener) {
            $listener->log($sectionName, $targetUrl, $rule, $success);
        }
    }

    public function finish() {
        foreach($this->listeners as $listener) {
            $listener->finish();
        }
    }
}