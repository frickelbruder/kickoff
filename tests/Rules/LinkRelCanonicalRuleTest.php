<?php
namespace Frickelbruder\KickOff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\LinkRelCanonicalRule;

class LinkRelCanonicalRuleTest extends \PHPUnit_Framework_TestCase {


    public function testValidate() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><link rel="canonical" href="/somewhere"></head></html>');

        $rule = new LinkRelCanonicalRule();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateDetectsEmptyTag() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><link rel="canonical" href=""></head></html>');

        $rule = new LinkRelCanonicalRule();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateDetectsDoubleTags() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><link rel="canonical" href="/somewhere"><link rel="canonical" href="/somewhere-else"></head></html>');

        $rule = new LinkRelCanonicalRule();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateOnMissingTags() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head></head></html>');

        $rule = new LinkRelCanonicalRule();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateDetectsHeader() {
        $response = new HttpResponse();
        $response->setHeaders(array('Link' => array('</some-cool-url>; rel=canonical')));

        $rule = new LinkRelCanonicalRule();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertTrue($result, $rule->getErrorMessage());
    }

    public function testValidateDetectsEmptyHeader() {
        $response = new HttpResponse();
        $response->setHeaders(array('Link' => array('<>; rel=canonical')));

        $rule = new LinkRelCanonicalRule();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateDetectsDoubleHeaders() {
        $response = new HttpResponse();
        $response->setHeaders(array('Link' => array('</some-cool-url>; rel=canonical', '</an-even-cooler-url>; rel=canonical')));

        $rule = new LinkRelCanonicalRule();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateOnMissingHeader() {
        $response = new HttpResponse();
        $response->setHeaders(array());

        $rule = new LinkRelCanonicalRule();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateWithBothCanonicalsSet() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head><link rel="canonical" href="/somewhere"></head></html>');
        $response->setHeaders(array('Link' => array('</some-cool-url>; rel=canonical')));

        $rule = new LinkRelCanonicalRule();
        $rule->setHttpResponse($response);

        $result = $rule->validate();
        $this->assertFalse($result);
    }




}
