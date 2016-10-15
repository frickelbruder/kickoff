<?php
namespace Frickelbruder\KickOff\Rules;


class HttpRequestTime extends ConfigurableRuleBase  {

    public $name = "HttpRequestTime";

    protected $errorMessage = 'This resource took too long to respond.';

    public $max = 1000;

    protected $configurableField = array("max");

    public function validate() {
        $duration = $this->httpResponse->getTransferTime();
        if($duration > $this->max) {
            $this->errorMessage = 'The resources took ' . $duration . ' ms to download. The max. allowed time is ' . $this->max . 'ms.';
            return false;
        }
        return true;
    }



}