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

    protected $errorMessage = 'The HTTP "Expires" header is not set to a far enough value';

    protected $configurableField = array('threshold');

    public function validate() {
        try {
            $expiresHeader = $this->findNormalizedHeader( 'Expires' );
            return strtotime( $expiresHeader ) >= time() + $this->threshold;

        } catch( HeaderNotFoundException $e ) {
            $this->errorMessage = $e->getMessage();
        }

        return false;
    }


}