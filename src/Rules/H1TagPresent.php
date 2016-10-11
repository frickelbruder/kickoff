<?php

namespace Frickelbruder\KickOff\Rules;

class H1TagPresent extends ConfigurableRuleBase {

    public $name = 'H1TagPresent';

    protected $allowMultipleTags = true;

    public function validate()
    {
        $amount = count($this->getCrawler()->filter('h1'));

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
