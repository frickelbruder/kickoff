<?php
namespace Frickelbruder\KickOff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\HttpHeaderStatusCode;

class HttpHeaderStatusCodeTest extends \PHPUnit_Framework_TestCase {

    public function testValidate() {
        $response = new HttpResponse();
        $response->setStatus(200);

        $rule = new HttpHeaderStatusCode();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateWrongDefaultStatus() {
        $response = new HttpResponse();
        $response->setStatus(302);

        $rule = new HttpHeaderStatusCode();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateRequestedCode() {
        $response = new HttpResponse();
        $response->setStatus(400);

        $rule = new HttpHeaderStatusCode();
        $rule->setValue(400);
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateRequestedCodeAndWrongGivenCode() {
        $response = new HttpResponse();
        $response->setStatus(302);

        $rule = new HttpHeaderStatusCode();
        $rule->setValue(400);
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

}
