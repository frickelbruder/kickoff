<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Contracts\HttpHeaderConfigurableRule;
use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;

class HttpHeaderPresent extends HttpHeaderConfigurableRule {

    public function validate() {
        parent::validate();
        try {
            $this->findNormalizedHeader($this->headerToSearchFor);
            return true;
        } catch(HeaderNotFoundException $e) {
            $this->errorMessage = $e->getMessage();
        }
        return false;
    }
}
