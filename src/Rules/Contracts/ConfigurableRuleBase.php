<?php
namespace Frickelbruder\KickOff\Rules\Contracts;

use Frickelbruder\KickOff\Rules\Contracts\RuleBase;
use Frickelbruder\KickOff\Rules\Exceptions\FieldNotConfigurableException;

abstract class ConfigurableRuleBase extends RuleBase implements ConfigurableRuleInterface {

    protected $configurableField = array();

    public function set($key, $value) {

        if(!in_array($key, $this->configurableField)) {
            throw new FieldNotConfigurableException("$key is not configurable");
        }

        $this->$key = $value;

    }


}
