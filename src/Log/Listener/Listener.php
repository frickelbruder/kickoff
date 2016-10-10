<?php
namespace Frickelbruder\KickOff\Log\Listener;

use Frickelbruder\KickOff\Rules\Contracts\RuleInterface;

Interface Listener {

    /**
     * @param string $sectionName
     * @param string $targetUrl
     * @param RuleInterface $rule
     * @param Boolean $success
     *
     * @return mixed
     */
    public function log($sectionName, $targetUrl, RuleInterface $rule, $success);

    public function finish();

}
