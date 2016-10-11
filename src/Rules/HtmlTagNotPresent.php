<?php
namespace Frickelbruder\KickOff\Rules;

class HtmlTagNotPresent extends ConfigurableRuleBase {

    protected $xpath = 'body';

    protected $configurableField = array('xpath');

    public function validate() {
        $tags = $this->getCrawler()->filterXPath($this->xpath);

        return !count($tags);
    }


}