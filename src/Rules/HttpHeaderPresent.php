<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;

class HttpHeaderPresent extends RuleBase  {

    /**
     * @var string
     */
    protected $headerToSearchFor = '';

    public function validate() {
        if(empty($this->headerToSearchFor)) {
            throw new \Exception();
        }
        $headers = $this->httpResponse->getHeaders();
        foreach($headers as $key => $value) {
            if(strtolower($key) == $this->headerToSearchFor) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $headerToSearchFor
     */
    public function setHeaderToSearchFor($headerToSearchFor) {
        $this->headerToSearchFor = strtolower($headerToSearchFor);
    }

    public function getErrorMessage() {
        return '%URL% does not have the "' . $this->headerToSearchFor . '" HTTP-header. (Rule "' . $this->getName(). '", "%SECTION%").';
    }


}