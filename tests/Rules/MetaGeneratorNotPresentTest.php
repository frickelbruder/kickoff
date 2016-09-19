<?php
namespace Frickelbruder\KickOff\Test\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\MetaGeneratorNotPresent;

class MetaGeneratorNotPresentTest extends \PHPUnit_Framework_TestCase {

    public function testValidate() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><meta name="description" content="I was not made by a generator"></head></html>');

        $rule = new MetaGeneratorNotPresent();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateWithMetaGeneratorPresent() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><meta name="generator" content="Some cool UI with weird HTML/CSS output"></head></html>');

        $rule = new MetaGeneratorNotPresent();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

}
