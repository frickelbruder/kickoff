<?php
namespace Frickelbruder\KickOff\Tests\Rules;

use Frickelbruder\KickOff\Rules\Contracts\ConfigurableRuleBase;

class RuleTestDummy extends ConfigurableRuleBase {

    public $field1 = true;

    public $field2 = true;

    protected $configurableField = array('field2');

    public function validate() {
        return true;
    }

    /**
     * @param boolean $field1
     */
    public function setField1($field1) {
        $this->field1 = $field1;
    }



}
