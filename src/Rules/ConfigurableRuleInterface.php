<?php
namespace Frickelbruder\KickOff\Rules;

interface ConfigurableRuleInterface extends RuleInterface {

    public function set($key, $value);

}