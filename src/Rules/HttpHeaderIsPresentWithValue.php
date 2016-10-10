<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Contracts\HttpHeaderConfigurableRule;
use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;

class HttpHeaderIsPresentWithValue extends HttpHeaderConfigurableRule {

    public function validate() {
        parent::validate();
        try {
            $value = $this->findNormalizedHeader($this->headerToSearchFor);
        } catch(HeaderNotFoundException $e) {
            $this->errorMessage = $e->getMessage();
            return false;
        }


        $this->errorMessage = 'The "' . $this->headerToSearchFor . '" HTTP-header does not have value "' . $this->value. '".';
        if($this->exactMatch == true) {
            return $value == $this->value;
        }
        return stripos($this->value, $value) !== false || stripos($value, $this->value) !== false;
    }
}
