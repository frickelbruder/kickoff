<?php

namespace Frickelbruder\KickOff\Rules;

class H1TagPresent extends ConfigurableRuleBase {

    public $name = 'Tag <H1> present Rule';

    protected $allowMultipleTags = true;

    public function validate()
    {
        $body   = $this->httpResponse->getBody();
        $xml    = $this->getResponseBodyAsXml($body);
        $amount = count($xml->xpath('//h1'));

        if (!$this->allowMultipleTags && $amount > 1) {
            $this->errorMessage = 'More than one H1 tag exists on this page.';
            return false;
        }

        if ($amount == 0) {
            $this->errorMessage = 'This page does not contain an H1 tag.';
            return false;
        }

        return true;
    }

    public function allowMultipleTags($allowMultipleTags)
    {
        $this->allowMultipleTags = $allowMultipleTags;
    }
}
