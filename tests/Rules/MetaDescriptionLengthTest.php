<?php
namespace Frickelbruder\KickOff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\MetaDescriptionLength;

class MetaDescriptionLengthTest extends \PHPUnit_Framework_TestCase {

    public function testValidate() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><meta name="description" content="This is a test with the desired length of a mediocre title"></head></html>');

        $rule = new MetaDescriptionLength();
        $rule->setHttpResponse($response);
        $rule->set('minlength', 10);
        $rule->set('maxlength', 300);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateWithTooShortTitle() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><meta name="description" content="Short"></head></html>');

        $rule = new MetaDescriptionLength();
        $rule->set('minlength', 100);
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateWithTooLongTitle() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><meta name="description" content="REALY LONG"></head></html>');

        $rule = new MetaDescriptionLength();
        $rule->set('minlength', 1);
        $rule->set('maxlength', 3);
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateWithMissingTitle() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head></head></html>');

        $rule = new MetaDescriptionLength();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateWithBrokenHtml() {
        $response = new HttpResponse();
        $response->setBody('<!Dml><html><hehead></html>');

        $rule = new MetaDescriptionLength();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateWithMultipleTitleTakesFirst() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><meta name="description" content="123456"><meta name="description" content="123"></head></html>');

        $rule = new MetaDescriptionLength();
        $rule->set('minlength', 6);
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateAsUtf8() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><meta name="description" content="äöü"></head></html>');
        $response->setHeaders(['Content-Type' => ['text/html; charset=UTF-8']]);

        $rule = new MetaDescriptionLength();
        $rule->set('minlength', 2);
        $rule->set('maxlength', 3);
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

}
