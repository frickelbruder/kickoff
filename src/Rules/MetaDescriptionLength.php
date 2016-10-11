<?php
namespace Frickelbruder\KickOff\Rules;

class MetaDescriptionLength extends ConfigurableRuleBase {

    public $name = 'MetaDescriptionLength';

    protected $minlength = 70;

    protected $maxlength = 160;

    protected $configurableField = array('minlength', 'maxlength');

    protected $errorMessage = 'The meta description on this page does not have the required length.';

    public function validate() {
        $metaDescriptionElement = $this->getCrawler()->filterXPath('./html/head/meta[@name="description"]');
        if (!count($metaDescriptionElement)) {
            $this->errorMessage = 'No meta description found';
            return false;
        }
        $length = mb_strlen( $metaDescriptionElement->eq(0)->attr('content'), 'UTF-8' );

        if($length < $this->minlength) {
            $this->errorMessage = 'The meta description is too short.';
            return false;
        }
        if( $length > $this->maxlength) {
            $this->errorMessage = 'The meta description is too long.';
            return false;
        }
        return true;

    }




}