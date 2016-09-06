<?php
namespace Frickelbruder\KickOff\Rules;

class MetaDescriptionLength extends RuleBase {

    public $name = 'MetaDescriptionLength';

    private $requiredLength = array('min' => 70, 'max' => 160);

    protected $errorMessage = 'The meta description on this page does not have the required length.';

    public function validate() {
        try {
            $body = $this->httpResponse->getBody();
            $xml = $this->getResponseBodyAsXml( $body );

            $metaDescriptionValue = $xml->xpath('/html/head/meta[@name="description"]/ @content');
            if(!is_array($metaDescriptionValue)) {
                $this->errorMessage = 'No meta description found';
                return false;
            }
            $length = mb_strlen( $metaDescriptionValue[0]['content'], 'UTF-8' );

            if($length < $this->requiredLength['min']) {
                $this->errorMessage = 'The meta description is too short.';
                return false;
            }
            if( $length > $this->requiredLength['max']) {
                $this->errorMessage = 'The meta description is too long.';
                return false;
            }
            return true;
        } catch(\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
        return false;
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