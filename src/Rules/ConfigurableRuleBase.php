<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Exceptions\FieldNotConfigurableException;
use Frickelbruder\KickOff\Rules\Interfaces\ConfigurableRuleInterface;

abstract class ConfigurableRuleBase extends RuleBase implements ConfigurableRuleInterface {

    protected $configurableField = array();

    public function set($key, $value) {

        if(!in_array($key, $this->configurableField)) {
            throw new FieldNotConfigurableException("$key is not configurable");
        }

        $this->$key = $value;

    }


}