<?php
namespace Frickelbruder\KickOff\Rules\Contracts;

use Frickelbruder\KickOff\Rules\Contracts\ConfigurableRuleBase;
use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;
use Frickelbruder\KickOff\Rules\Exceptions\InsufficientConfigurationException;

class HttpHeaderConfigurableRule extends ConfigurableRuleBase {

    /**
     * @var string
     */
    protected $headerToSearchFor = '';

    protected $value = '';

    protected $exactMatch = true;

    protected $configurableField = array("value");


    public function validate() {
        if(empty($this->headerToSearchFor)) {
            throw new InsufficientConfigurationException('"headerToSearchFor" not set for ' . $this->getName());
        }
    }

    /**
     * @param string $headerToSearchFor
     */
    public function setHeaderToSearchFor($headerToSearchFor) {
        $this->headerToSearchFor = strtolower($headerToSearchFor);
    }

    /**
     * @param boolean $exactMatch
     */
    public function setExactMatch($exactMatch) {
        $this->exactMatch = $exactMatch;
    }

    /**
     * @param string $value
     */
    public function setValue($value) {
        $this->value = $value;
    }




}
