<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Contracts\RuleBase;

class AppleTouchIcon extends RuleBase {

    public $name = 'AppleTouchIcon';

    protected $xpath = '/html/head/link[@rel="apple-touch-icon"]';

    protected $errorMessage = 'No apple touch icon meta header was found.';

    public function validate() {

        $icons = $this->getDomElementFromBodyByXpath($this->xpath);

        $foundItems = array();
        foreach($icons as $icon) {
            $size = empty($icon['sizes']) ? 'default' : (string) $icon['sizes'][0];
            if(isset($foundItems[$size])) {
                $this->errorMessage = 'Multiple apple-touch-icon entries for size "' . $size . '" found.';
                return false;
            }
            $foundItems[$size] = $icon;
        }

        return !empty($icons);
    }


}
