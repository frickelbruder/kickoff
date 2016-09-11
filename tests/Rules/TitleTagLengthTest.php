<?php
namespace Frickelbruder\Kickoff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\TitleTagLength;

class TitleTagLengthTest extends \PHPUnit_Framework_TestCase {

    public function testValidate() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><title>This is a test with the desired length of a good title</title></head></html>');

        $rule = new TitleTagLength();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateWithTooShortTitle() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><title>Short</title></head></html>');

        $rule = new TitleTagLength();
        $rule->set('minlength', 100);
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateWithTooLongTitle() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><title>REALY LONG</title></head></html>');

        $rule = new TitleTagLength();
        $rule->set('maxlength', 3);
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateWithMissingTitle() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head></head></html>');

        $rule = new TitleTagLength();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateWithMultipleTitleTakesFirst() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><title>123456</title><title>123</title></head></html>');

        $rule = new TitleTagLength();
        $rule->set('minlength', 6);
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateAsUtf8() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><title>öäü</title></head></html>');

        $rule = new TitleTagLength();
        $rule->set('minlength', 2);
        $rule->set('maxlength', 3);
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

}
