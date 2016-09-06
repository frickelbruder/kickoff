<?php
namespace Frickelbruder\KickOff\Log;

use Frickelbruder\KickOff\Log\Exceptions\ListenerNotFoundException;
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
    public function addListener($name, Listener $listener) {
        $this->listeners[$name] = $listener;
    }

    /**
     * @param string $name
     */
    public function removeListener($name) {
        if(isset($this->listeners[$name])) {
            unset($this->listeners[$name]);
        }
    }

    /**
     * @param string $name
     *
     * @return Listener
     */
    public function getListener($name) {
        if(empty($this->listeners[$name])) {
            throw new ListenerNotFoundException('Listener ' . $name . ' not found');
        }
        return ($this->listeners[$name]);
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