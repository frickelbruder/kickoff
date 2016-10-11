<?php
namespace Frickelbruder\KickOff\Rules;

class AppleTouchIcon extends RuleBase {

    public $name = 'AppleTouchIcon';

    protected $xpath = './html/head/link[@rel="apple-touch-icon"]';

    protected $errorMessage = 'No apple touch icon meta header was found.';

    public function validate() {

        $icons = $this->getCrawler()->filterXPath($this->xpath);
        $sizeAttributes = $icons->extract(['sizes']);
        $foundItems = array();

        foreach ($sizeAttributes as $size) {
            if (isset($foundItems[$size])) {
                $this->errorMessage = 'Multiple apple-touch-icon entries for size "' . $size . '" found.';
                return false;
            }

            $foundItems[$size] = $size;
        }

        return count($icons) > 0;
    }


}
