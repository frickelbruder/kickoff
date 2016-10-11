<?php
namespace Frickelbruder\KickOff\Rules;

class TitleTagLength extends ConfigurableRuleBase {

    public $name = 'TitleTagLength';

    protected $minlength = 10;

    protected $maxlength = 80;

    protected $configurableField = array( 'minlength', 'maxlength' );

    protected $errorMessage = 'The title tag on this page does not have the required length.';

    public function validate() {
        $titleTag = $this->getCrawler()->filterXPath('./html/head/title');

        if (!count($titleTag)) {
            $this->errorMessage = 'The title tag was not found.';

            return false;
        }
        ( $length = mb_strlen( $titleTag->text(), 'UTF-8' ) );

        if( $length < $this->minlength ) {
            $this->errorMessage = 'The title tag is too short.';

            return false;
        }
        if( $length > $this->maxlength ) {
            $this->errorMessage = 'The title tag is too long.';

            return false;
        }

        return true;

    }

}