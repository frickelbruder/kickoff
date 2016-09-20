<?php
namespace Frickelbruder\KickOff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\TwitterProperty;

class TwitterPropertyTest extends \PHPUnit_Framework_TestCase {

    private $testString = '<!DOCTYPE html><html><head><meta name="twitter:title" content="Twitter Title"><meta name="twitter:description" content="Twitter Description"><meta name="twitter:card" content="summary"><meta name="twitter:image" content=""></head></html>';


    public function testValidate() {
        $response = new HttpResponse();
        $response->setBody( $this->testString );

        $rule = new TwitterProperty();
        $rule->setHttpResponse( $response );
        $rule->set( 'requiredProperties', array( 'title', 'description' ) );

        $result = $rule->validate();
        $this->assertTrue( $result );
    }

    public function testValidateWithEmptyPropertyIsFalse() {
        $response = new HttpResponse();
        $response->setBody( $this->testString );

        $rule = new TwitterProperty();
        $rule->setHttpResponse( $response );
        $rule->set( 'requiredProperties', array( 'title', 'description', 'image' ) );

        $result = $rule->validate();
        $this->assertFalse( $result );
    }

    public function testValidateWithNotFoundPropertyIsFalse() {
        $response = new HttpResponse();
        $response->setBody( $this->testString );

        $rule = new TwitterProperty();
        $rule->setHttpResponse( $response );
        $rule->set( 'requiredProperties', array( 'title', 'description', 'image:secure_url' ) );

        $result = $rule->validate();
        $this->assertFalse( $result );
    }

}
