<?php
namespace Frickelbruder\Kickoff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\HttpHeaderHasFarFutureExpiresHeader;

class HttpHeaderHasFarFutureExpiresHeaderTest extends \PHPUnit_Framework_TestCase {

    public function testValidate() {
        $threshold = 7 * 24 * 60 * 60; //Default
        $response = new HttpResponse();
        $response->setHeaders(array('Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + $threshold)));

        $rule = new HttpHeaderHasFarFutureExpiresHeader();
        $rule->setThresholdInSeconds($threshold);
        $rule->setHttpResponse($response);


        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateFalseWithJustExpiringHeader() {
        $threshold = 7 * 24 * 60 * 60; //Default
        $response = new HttpResponse();
        $response->setHeaders(array('Expires' => gmdate('D, d M Y H:i:s \G\M\T', time())));

        $rule = new HttpHeaderHasFarFutureExpiresHeader();
        $rule->setThresholdInSeconds($threshold);
        $rule->setHttpResponse($response);


        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateFalseWithoutExpiresHeader() {
        $threshold = 7 * 24 * 60 * 60; //Default
        $response = new HttpResponse();
        $response->setHeaders(array('Expiring' => 'anytime'));

        $rule = new HttpHeaderHasFarFutureExpiresHeader();
        $rule->setThresholdInSeconds($threshold);
        $rule->setHttpResponse($response);


        $result = $rule->validate();
        $this->assertFalse($result);
    }


}
