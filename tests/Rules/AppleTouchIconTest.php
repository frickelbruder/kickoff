<?php
namespace Frickelbruder\KickOff\Test\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\AppleTouchIcon;

class AppleTouchIconTest extends \PHPUnit_Framework_TestCase {

    public function testValidate() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><link rel="apple-touch-icon" href="/somewhere.png"></head></html>');

        $rule = new AppleTouchIcon();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateWithMultipleSizes() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><link rel="apple-touch-icon" href="/somewhere.png">
<link rel="apple-touch-icon" sizes="60x60" href="/somewhere60x60.png">
<link rel="apple-touch-icon" sizes="160x160" href="/somewhere160x160.png"></head></html>');

        $rule = new AppleTouchIcon();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateWithMultipleSizesFilesOnDuplicateSize() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><link rel="apple-touch-icon" href="/somewhere.png">
<link rel="apple-touch-icon" sizes="60x60" href="/somewhere60x60.png">
<link rel="apple-touch-icon" sizes="60x60" href="/somewhere160x160.png"></head></html>');

        $rule = new AppleTouchIcon();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }


    public function testValidateWithMultipleSizesWithMultipleDefault() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><link rel="apple-touch-icon" href="/somewhere.png">
<link rel="apple-touch-icon" sizes="60x60" href="/somewhere60x60.png">
<link rel="apple-touch-icon" href="/somewhere160x160.png"></head></html>');

        $rule = new AppleTouchIcon();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }


    public function testValidateWithMultipleSizesWithoutIcons() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head></head></html>');

        $rule = new AppleTouchIcon();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }
}
