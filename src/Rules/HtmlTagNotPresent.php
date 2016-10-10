<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Contracts\ConfigurableRuleBase;

class HtmlTagNotPresent extends ConfigurableRuleBase {

    protected $xpath = 'body';

    protected $configurableField = array('xpath');

    public function validate() {
        $result = $this->getDomElementFromBodyByXpath($this->xpath);

        return empty($result);
    }


}
