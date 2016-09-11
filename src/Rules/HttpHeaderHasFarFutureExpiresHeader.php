<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;

class HttpHeaderHasFarFutureExpiresHeader extends ConfigurableRuleBase {

    public $name = 'Far future "Expires" header';

    /**
     * Defaults to 7 days (7*24*60*60)
     *
     * @var integer
     */
    protected $threshold = 604800;

    protected $configurableField = array('threshold');

    public function validate() {
        try {
            $expiresHeader = $this->findHeader( 'Expires' );
            $result = strtotime( $expiresHeader ) >= time() + $this->threshold;
            if(!$result) {
                $this->errorMessage = 'The HTTP "Expires" header is not set to a far enough value';
            }
            return $result;

        } catch( HeaderNotFoundException $e ) {
            $this->errorMessage = $e->getMessage();
        }

        return false;
    }


}