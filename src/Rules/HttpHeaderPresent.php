<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;
use Frickelbruder\KickOff\Rules\Exceptions\InsufficientConfigurationException;

class HttpHeaderPresent extends RuleBase   {

    /**
     * @var string
     */
    protected $headerToSearchFor = '';

    public function validate() {
        if(empty($this->headerToSearchFor)) {
            throw new InsufficientConfigurationException('"headerToSearchFor" not set for ' . $this->getName());
        }
        try {
            $this->findNormalizedHeader($this->headerToSearchFor);
            return true;
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

}