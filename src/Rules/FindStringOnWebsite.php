<?php
namespace Frickelbruder\KickOff\Rules;

class FindStringOnWebsite extends ConfigurableRuleBase {

    public $name = 'Find string on website';

    protected $configurableField = array('stringToSearchFor');

    protected $stringToSearchFor = '';

    public function validate() {
        return strstr($this->httpResponse->getBody(), $this->stringToSearchFor) !== false;
    }

}