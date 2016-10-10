<?php
namespace Frickelbruder\KickOff\Rules\Contracts;

interface ConfigurableRuleInterface extends RuleInterface {

    function set($key, $value);

}
