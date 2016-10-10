<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Contracts\HttpHeaderRule;
use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;

class HttpHeaderNotPresent extends HttpHeaderRule {

    /**
     *
     * @return bool
     * @throws InsufficientConfigurationException
     */
    public function validate() {
        parent::validate();
        try {
            $this->findNormalizedHeader($this->headerToSearchFor);
            return false;
        } catch(HeaderNotFoundException $e) {}
        return true;
    }

    public function getErrorMessage() {
        return 'The "' . $this->headerToSearchFor . '" HTTP-header was found, but should not be there.';
    }
}
