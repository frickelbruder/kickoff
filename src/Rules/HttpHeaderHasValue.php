<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;
use Frickelbruder\KickOff\Rules\Exceptions\InsufficientConfigurationException;

class HttpHeaderHasValue extends RuleBase {

    /**
     * @var string
     */
    protected $headerToSearchFor = '';

    protected $value = '';

    protected $exactMatch = true;


    public function validate() {
        try {
            if( empty( $this->headerToSearchFor ) ) {
                throw new InsufficientConfigurationException('"headerToSearchFor" not set for ' . $this->getName());
            }
            $value = $this->findHeader($this->headerToSearchFor);
            if($this->exactMatch == true && $value == $this->value) {
                return true;
            }
            if($this->exactMatch == false && (stripos($this->value, $value) !== false || stripos($value, $this->value) !== false)) {
                return true;
            }
        } catch(HeaderNotFoundException $e) {
            $this->errorMessage = $e->getMessage();
        }

        return false;
    }

    /**
     * @param string $headerToSearchFor
     */
    public function setHeaderToSearchFor($headerToSearchFor) {
        $this->headerToSearchFor = strtolower($headerToSearchFor);
    }

    public function getErrorMessage() {
        return 'The "' . $this->headerToSearchFor . '" HTTP-header does not have value "' . $this->value. '".';
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