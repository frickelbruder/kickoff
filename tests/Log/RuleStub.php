<?php
namespace Frickelbruder\KickOff\Tests\Log;

use Frickelbruder\KickOff\Rules\Contracts\RuleBase;

class RuleStub extends RuleBase {

    public $result = true;

    public $name = 'RuleStub';

    public function validate() {
        return $this->result;
    }


}
