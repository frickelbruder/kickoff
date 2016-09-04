<?php
namespace Frickelbruder\KickOff\Log\Listener;

Interface Listener {

    /**
     * @param string $sectionName
     * @param string $targetUrl
     * @param string $ruleName
     * @param Boolean $success
     *
     * @return mixed
     */
    public function log($sectionName, $targetUrl, $ruleName, $success);

}