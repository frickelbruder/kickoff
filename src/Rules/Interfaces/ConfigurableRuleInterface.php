<?php
namespace Frickelbruder\KickOff\Rules\Interfaces;

interface ConfigurableRuleInterface extends RuleInterface {

    public function set($key, $value);

}