<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;
use Frickelbruder\KickOff\Rules\Exceptions\InsufficientConfigurationException;

class HttpHeaderNotPresent extends RuleBase {

     /**
     * @var string
     */
    protected $headerToSearchFor = '';

    /**
     *
     * @return bool
     * @throws InsufficientConfigurationException
     */
    public function validate() {
        if(empty($this->headerToSearchFor)) {
            throw new InsufficientConfigurationException('"headerToSearchFor" not set for ' . $this->getName());
        }
        try {
            $this->findHeader($this->headerToSearchFor);
            return false;
        } catch(HeaderNotFoundException $e) {}
        return true;
    }

    /**
     * @param string $headerToSearchFor
     */
    public function setHeaderToSearchFor($headerToSearchFor) {
        $this->headerToSearchFor = strtolower($headerToSearchFor);
    }

    public function getErrorMessage() {
        return 'The "' . $this->headerToSearchFor . '" HTTP-header was found, but should not be there.';
    }



}