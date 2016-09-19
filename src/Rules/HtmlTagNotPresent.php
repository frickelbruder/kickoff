<?php
namespace Frickelbruder\KickOff\Rules;

class HtmlTagNotPresent extends ConfigurableRuleBase {

    protected $xpath = 'body';

    protected $configurableField = array('xpath');

    public function validate() {
        $result = $this->getDomElementFromBodyByXpath($this->xpath);

        return empty($result);
    }


}