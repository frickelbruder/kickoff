<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;

class HttpHeaderHasFarFutureExpiresHeader extends HttpRuleBase {

    public $name = 'Far future "Expires" header';

    /**
     * Defaults to 7 days (7*24*60*60)
     *
     * @var integer
     */
    private $thresholdInSeconds = 604800;

    public function validate() {
        try {
            $expiresHeader = $this->findHeader( 'Expires' );

            return strtotime( $expiresHeader ) > time() + $this->thresholdInSeconds;

        } catch( HeaderNotFoundException $e ) {
        }

        return false;
    }

    public function getErrorMessage() {
        return '%URL% does not have any or to short "Expires" HTTP header information. (Rule "' . $this->getName() . '", "%SECTION%").';
    }

    /**
     * @param int $thresholdInSeconds
     */
    public function setThresholdInSeconds($thresholdInSeconds) {
        $this->thresholdInSeconds = $thresholdInSeconds;
    }

}