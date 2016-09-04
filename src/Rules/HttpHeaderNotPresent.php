<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;

class HttpHeaderNotPresent extends HttpRuleBase {

     /**
     * @var string
     */
    protected $headerToSearchFor = '';

    /**
     *
     * @return bool
     * @throws \Exception
     */
    public function validate() {
        if(empty($this->headerToSearchFor)) {
            throw new \Exception();
        }
        try {
            $this->findHeader($this->headerToSearchFor);
            return false;
        } catch(HeaderNotFoundException $e) {}
        return true;
    }

    /**
     * @param string $headerToSearchFor
     */
    public function setHeaderToSearchFor($headerToSearchFor) {
        $this->headerToSearchFor = strtolower($headerToSearchFor);
    }

    public function getErrorMessage() {
        return '%URL% has the "' . $this->headerToSearchFor . '" HTTP-header present, which shouldn\'t. (Rule "' . $this->getName(). '", "%SECTION%").';
    }



}