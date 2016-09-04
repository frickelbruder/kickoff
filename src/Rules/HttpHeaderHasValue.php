<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;

class HttpHeaderHasValue extends HttpRuleBase {


    /**
     * @var string
     */
    protected $headerToSearchFor = '';

    protected $value = '';

    protected $exactMatch = true;


    public function validate() {
        try {
            if( empty( $this->headerToSearchFor ) ) {
                throw new \Exception();
            }
            $value = $this->findHeader($this->headerToSearchFor);
            if($this->exactMatch == true && $value == $this->value) {
                return true;
            }
            if($this->exactMatch == false && (stripos($this->value, $value) !== false || stripos($value, $this->value) !== false)) {
                return true;
            }
        } catch(HeaderNotFoundException $e) {}
        catch(\Exception $e) {}

        return false;
    }

    /**
     * @param string $headerToSearchFor
     */
    public function setHeaderToSearchFor($headerToSearchFor) {
        $this->headerToSearchFor = strtolower($headerToSearchFor);
    }

    public function getErrorMessage() {
        return '%URL% does not have value "' . $this->value. '" for the "' . $this->headerToSearchFor . '" HTTP-header. (Rule "' . $this->getName(). '", "%SECTION%").';
    }

    /**
     * @param boolean $exactMatch
     */
    public function setExactMatch($exactMatch) {
        $this->exactMatch = $exactMatch;
    }


}