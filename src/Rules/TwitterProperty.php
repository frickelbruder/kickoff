<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Contracts\ConfigurableRuleBase;

class TwitterProperty extends ConfigurableRuleBase {

    public $name = 'TwitterProperty';

    protected $errorMessage = 'The required twitter property is not present.';

    protected $requiredProperties = array( 'card', 'title', 'description', 'image:src' );

    private $cardValues = array('summary', 'summary_large_image', 'player', 'product', 'photo', 'gallery', 'app');

    protected $configurableField = array( 'requiredProperties' );

    public function validate() {
        $body = $this->httpResponse->getBody();
        $xml = $this->getResponseBodyAsXml( $body );

        return $this->checkRequiredProperties( $xml );
    }

    /**
     * @param \SimpleXMLElement $body
     *
     * @return bool
     */
    private function checkRequiredProperties($body) {
        foreach( $this->requiredProperties as $propertyName ) {
            $propertyItemValue = $body->xpath( '/html/head/meta[@name="twitter:' . $propertyName . '"]/ @content' );
            if( !is_array( $propertyItemValue ) || empty( $propertyItemValue ) ) {
                $this->errorMessage = 'The twitter property "' . $propertyName . '" was not found on the site.';

                return false;
            }

            if(!$this->validateProperty($propertyName, $propertyItemValue[0]['content'])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $propertyName
     * @param $itemValue
     * @return bool
     */
    private function validateProperty($propertyName, $itemValue) {

        $length = mb_strlen( $itemValue, 'UTF-8' );
        if ($length == 0) {
            $this->errorMessage = 'The twitter property "' . $propertyName . '" was found but is empty.';
            return false;
        }

        if ($propertyName == 'card' && !in_array($itemValue, $this->cardValues)) {
            $this->errorMessage = 'The value for the "twitter:card" meta tag is invalid';
            return false;
        }

        if ($propertyName == 'title' && $length > 70) {
            $this->errorMessage = 'The value for the "twitter:title" meta tag is too long. It should only be 70 characters long.';
            return false;
        }

        if ($propertyName == 'description' && $length > 200) {
            $this->errorMessage = 'The value for the "twitter:description" meta tag is too long. It should only be 200 characters long.';
            return false;
        }
        return true;
    }
}
