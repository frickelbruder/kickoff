<?php
namespace Frickelbruder\KickOff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\XuaCompatible;

class XuaCompatibleTest extends \PHPUnit_Framework_TestCase {

    private $testString = '<!DOCTYPE html><html><head><meta http-equiv="X-UA-Compatible" content="IE=edge"></head><body></body></html>';


    public function testValidate() {
        $response = new HttpResponse();
        $response->setBody( $this->testString );

        $rule = new XuaCompatible();
        $rule->setHttpResponse( $response );

        $result = $rule->validate();
        $this->assertTrue( $result, $rule->getErrorMessage() );
    }

    public function testValidateWithMissingDirectiveIsFalse() {
        $response = new HttpResponse();
        $response->setBody( '<!DOCTYPE html><html><head></head><body></body></html>' );

        $rule = new XuaCompatible();
        $rule->setHttpResponse( $response );

        $result = $rule->validate();
        $this->assertFalse( $result );
    }

    public function testValidateWithUnexpectedValueIsFalse() {
        $testString = '<!DOCTYPE html><html><head><title>Test</title><meta name="description" content="description"><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"></head><body></body></html>';
        $response = new HttpResponse();
        $response->setBody( $testString );

        $rule = new XuaCompatible();
        $rule->setHttpResponse( $response );

        $result = $rule->validate();
        $this->assertFalse( $result );
    }

    public function testValidateWithUnexpectedConfiguredValueIsFalse() {
        $response = new HttpResponse();
        $response->setBody( $this->testString );

        $rule = new XuaCompatible();
        $rule->setHttpResponse( $response );
        $rule->set('expectedValue', 'IE=7');

        $result = $rule->validate();
        $this->assertFalse( $result );
    }

    public function testValidateWithExpectedValueButNotTooLateInDocumentIsFalse() {
        $testString = '<!DOCTYPE html><html><head><title>Test</title><meta name="description" content="description"><script>something();</script><meta http-equiv="X-UA-Compatible" content="IE=edge"></head><body></body></html>';
        $response = new HttpResponse();
        $response->setBody( $testString );

        $rule = new XuaCompatible();
        $rule->setHttpResponse( $response );

        $result = $rule->validate();
        $this->assertFalse( $result );
    }


    public function testValidateWithHeaderSetDefault() {
        $response = new HttpResponse();
        $response->setBody( $this->testString );
        $response->setHeaders(array('X-UA-Compatible' => 'IE=edge'));

        $rule = new XuaCompatible();
        $rule->setHttpResponse( $response );

        $result = $rule->validate();
        $this->assertTrue( $result );
    }

    public function testValidateWithHeaderSetAndUnexpectedValue() {
        $response = new HttpResponse();
        $response->setBody( $this->testString );
        $response->setHeaders(array('X-UA-Compatible' => 'IE=edge'));

        $rule = new XuaCompatible();
        $rule->setHttpResponse( $response );
        $rule->set('expectedValue', 'IE=7');

        $result = $rule->validate();
        $this->assertFalse( $result );
    }



}
