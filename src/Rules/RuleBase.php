<?php
namespace Frickelbruder\KickOff\Rules;

abstract class RuleBase implements Rule {

    public $name = '';

    public function getName() {
        return $this->name;
    }

}