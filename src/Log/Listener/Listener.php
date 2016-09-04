<?php
namespace Frickelbruder\KickOff\Log\Listener;

use Frickelbruder\KickOff\Rules\Rule;

Interface Listener {

    /**
     * @param string $sectionName
     * @param string $targetUrl
     * @param Rule $rule
     * @param Boolean $success
     *
     * @return mixed
     */
    public function log($sectionName, $targetUrl, Rule $rule, $success);

    public function finish();

}