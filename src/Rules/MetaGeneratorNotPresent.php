<?php
namespace Frickelbruder\KickOff\Rules;

class MetaGeneratorNotPresent extends ConfigurableRuleBase {

    public $name = 'MetaGeneratorNotPresent';

    protected $errorMessage = 'The <meta name="generator" content="..."> tag must nor be present.';

    protected $xpath = '/html/head/meta[@name="generator"]/ @content';

    public function validate() {
        $body = $this->httpResponse->getBody();
        $xml = $this->getResponseBodyAsXml( $body );

        $result = $xml->xpath( $this->xpath );

        return empty($result);
    }

}