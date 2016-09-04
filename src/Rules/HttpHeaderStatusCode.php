<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;

class HttpHeaderStatusCode extends HttpRuleBase {

    public $name = "HttpHeaderStatusCode";

    /**
     * @var HttpResponse
     */
    protected $item;

    protected $value = 200;

    public function setItemToValidate(HttpResponse $item) {
        $this->item = $item;
    }

    public function validate() {
        return $this->item->getStatus() == $this->value;
    }

    public function getErrorMessage() {
        return '%URL% does not have HTTP status "' . $this->value . '". (Rule "' . $this->getName() . '", "%SECTION%").';
    }

    /**
     * @param int $value
     */
    public function setValue($value) {
        $this->value = $value;
    }

}