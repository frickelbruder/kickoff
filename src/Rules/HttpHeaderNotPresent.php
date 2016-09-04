<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;

class HttpHeaderNotPresent extends RuleBase  {

    /**
     * @var HttpResponse
     */
    protected $item;

    /**
     * @var string
     */
    protected $headerToSearchFor = '';

    public function setItemToValidate(HttpResponse $item) {
        $this->item = $item;
    }

    /**
     *
     * @return bool
     * @throws \Exception
     */
    public function validate() {
        if(empty($this->headerToSearchFor)) {
            throw new \Exception();
        }
        $headers = $this->item->getHeaders();
        foreach($headers as $key => $value) {
            if(strtolower($key) == $this->headerToSearchFor) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param string $headerToSearchFor
     */
    public function setHeaderToSearchFor($headerToSearchFor) {
        $this->headerToSearchFor = strtolower($headerToSearchFor);
    }

    public function getErrorMessage() {
        return '%URL% has the "' . $this->headerToSearchFor . '" HTTP-header present, which it shouldn\'t. (Rule "' . $this->getName(). '", "%SECTION%").';
    }



}