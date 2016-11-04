<?php
namespace Frickelbruder\KickOff\Rules;


class HttpHeaderStatusCode extends ConfigurableRuleBase  {

    public $name = "Http header: Status code";

    protected $errorMessage = 'The resources HTTP status code was unexpected.';

    protected $value = 200;

    protected $configurableField = array('value');

    public function validate() {
        $resultCode = $this->httpResponse->getStatus();
        if($resultCode != $this->value) {
            $this->errorMessage = 'The resources HTTP status code is "' . $resultCode . '" not "' . $this->value . '".';
            return false;
        }
        return true;
    }

    /**
     * @param int $value
     */
    public function setValue($value) {
        $this->value = $value;
    }

}