<?php
namespace Frickelbruder\KickOff\Rules;

class HtmlTagNotPresent extends ConfigurableRuleBase {

    protected $xpath = 'body';

    protected $configurableField = array('xpath');

    public function validate() {
        $body = $this->httpResponse->getBody();
        $xml = $this->getResponseBodyAsXml( $body );

        $result = $xml->xpath( $this->xpath );
        return empty($result);
    }


}