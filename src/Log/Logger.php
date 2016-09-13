<?php
namespace Frickelbruder\KickOff\Log;

use Frickelbruder\KickOff\Log\Exceptions\ListenerNotFoundException;
use Frickelbruder\KickOff\Log\Listener\Listener;
use Frickelbruder\KickOff\Rules\RuleInterface;


class Logger {

    /**
     * @var Listener[]
     */
    private $listeners = array();

    /**
     * @param $name
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
     * @throws ListenerNotFoundException
     */
    public function getListener($name) {
        if(empty($this->listeners[$name])) {
            throw new ListenerNotFoundException('Listener ' . $name . ' not found');
        }
        return $this->listeners[$name];
    }

    /**
     * @param string $sectionName
     * @param string $targetUrl
     * @param RuleInterface $rule
     *
     */
    public function logSuccess($sectionName, $targetUrl, RuleInterface $rule) {
        $this->log($sectionName, $targetUrl, $rule, true);
    }

    /**
     * @param string $sectionName
     * @param string $targetUrl
     * @param RuleInterface $rule
     */
    public function logFail($sectionName, $targetUrl, RuleInterface $rule) {
        $this->log($sectionName, $targetUrl, $rule, false);
    }

    /**
     * @param string $sectionName
     * @param string $targetUrl
     * @param RuleInterface $rule
     * @param Boolean $success
     */
    public function log($sectionName, $targetUrl, RuleInterface $rule, $success) {
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