<?php
namespace Frickelbruder\Kickoff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\HttpHeaderStatusCode;
use Frickelbruder\KickOff\Rules\HttpRequestTime;

class HttRequestTimeTest extends \PHPUnit_Framework_TestCase {

    public function testValidateDefault() {
        $response = new HttpResponse();
        $response->setTransferTime(200);

        $rule = new HttpRequestTime();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateTrueWithTransferTimeSet() {
        $response = new HttpResponse();
        $response->setTransferTime(200);

        $rule = new HttpRequestTime();
        $rule->set("maxTransferTime", 2000);
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateTrueOnEqualTimeAsMaxTime() {
        $response = new HttpResponse();
        $response->setTransferTime(200);

        $rule = new HttpRequestTime();
        $rule->set("maxTransferTime", 200);
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateFalseOnSlowerThanMaxTime() {
        $response = new HttpResponse();
        $response->setTransferTime(2000);

        $rule = new HttpRequestTime();
        $rule->set("maxTransferTime", 200);
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }


}
