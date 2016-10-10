<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Contracts\HttpHeaderConfigurableRule;
use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;

class HttpHeaderHasValue extends HttpHeaderConfigurableRule {

    public function validate() {
        try {
            parent::validate();
            $value = $this->findNormalizedHeader($this->headerToSearchFor);
            if($this->exactMatch == true) {
                return $value == $this->value;
            }
            return stripos($this->value, $value) !== false || stripos($value, $this->value) !== false;
        } catch(HeaderNotFoundException $e) {
            $this->errorMessage = $e->getMessage();
        }

        return false;
    }

    public function getErrorMessage() {
        return 'The "' . $this->headerToSearchFor . '" HTTP-header does not have value "' . $this->value. '".';
    }
}
