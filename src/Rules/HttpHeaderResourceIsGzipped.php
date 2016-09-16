<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;

class HttpHeaderResourceIsGzipped extends RuleBase implements RequiresHeaderInterface {

    public $name = 'HttpHeaderResourceIsGzipped';

    protected $errorMessage =  'The "Content-Encoding" HTTP header was found but had an unexpected value';

    public function getRequiredHeaders() {
        return array(array('Accept-Encoding', 'gzip, deflate'));
    }

    public function validate() {
        try {
            $contentEncoding = $this->findNormalizedHeader( 'Content-Encoding' );
            return $contentEncoding == 'gzip';
        } catch(HeaderNotFoundException $e) {
            $this->errorMessage = 'The "Content-Encoding" HTTP header was not found.';
        }
        return false;
    }


}