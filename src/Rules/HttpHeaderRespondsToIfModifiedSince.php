<?php
namespace Frickelbruder\KickOff\Rules;

class HttpHeaderRespondsToIfModifiedSince extends RuleBase implements RequiresHeaderInterface {

    public function getRequiredHeaders() {
        return array(array('If-Modified-Since', gmdate('D, d M Y H:i:s T', time())));
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