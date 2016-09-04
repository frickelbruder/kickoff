<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;

class HttpHeaderHasValue extends RuleBase {

    /**
     * @var HttpResponse
     */
    protected $item;

    /**
     * @var string
     */
    protected $headerToSearchFor = '';

    protected $value = '';

    protected $exactMatch = true;


    public function setItemToValidate(HttpResponse $item) {
        $this->item = $item;
    }

    public function validate() {
        if(empty($this->headerToSearchFor)) {
            throw new \Exception();
        }
        $headers = $this->item->getHeaders();
        foreach($headers as $key => $value) {
            if(strtolower($key) == $this->headerToSearchFor) {
                if($this->exactMatch == true && $value == $this->value) {
                    return true;
                }
                if($this->exactMatch == false && (stripos($this->value, $value) !== false || stripos($value, $this->value) !== false)) {
                    return true;
                }
                return false;
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


}