<?php
namespace Frickelbruder\KickOff\Rules;

class TitleTagLength extends RuleBase {

    public $name = 'TitleTagLength';

    private $requiredLength = array('min' => 10, 'max' => 70);

    protected $errorMessage = 'The title tag on this page does not have the required length.';

    public function validate() {
        try {
            $body = $this->httpResponse->getBody();
            $xml = $this->getResponseBodyAsXml( $body );

            $titleTagValue = $xml->head->title;

            if(empty($titleTagValue)) {
                $this->errorMessage = 'The title tag was not found.';
                return false;
            }
            ($length = mb_strlen( $titleTagValue, 'UTF-8' ));

            if($length < $this->requiredLength['min']) {
                $this->errorMessage = 'The title tag is too short.';
                return false;
            }
            if( $length > $this->requiredLength['max']) {
                $this->errorMessage = 'The title tag is too long.';
                return false;
            }
            return true;
        } catch(\Exception $e) {
            $this->errorMessage = $e->getMessage();
            return false;
        }
    }

    public function getErrorMessage() {
        return $this->errorMessage;
    }

    public function setMinLength($min) {
        $this->requiredLength['min'] = $min;
    }

    public function setMaxLength($max) {
        $this->requiredLength['max'] = $max;
    }
}