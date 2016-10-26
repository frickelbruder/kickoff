<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Interfaces\RequiresHeaderInterface;

class HttpHeaderRespondsToIfModifiedSince extends RuleBase implements RequiresHeaderInterface {

    public $name = 'HTTP header: Server respopnds to "If-Modified-Since"';

    public function getRequiredHeaders() {
        return array('If-Modified-Since' => gmdate('D, d M Y H:i:s T', time()));
    }

    public function validate() {
        $status = $this->httpResponse->getStatus();
        if($status == 200) {
            $this->errorMessage = "The resource was fully delivered, though 'If-Modified-Since' header was set.";
            return false;
        }
        if($status != 304) {
            $this->errorMessage = "The resource responded with an unexpected status code '" . $status . "' to 'If-Modified-Since'.";
            return false;
        }
        return true;
    }


}