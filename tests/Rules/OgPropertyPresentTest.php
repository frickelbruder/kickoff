<?php
namespace Frickelbruder\KickOff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\OgPropertyPresent;

class OgPropertyPresentTest extends \PHPUnit_Framework_TestCase {

    private $testString = '<!DOCTYPE html><html><head><meta property="og:title" content="Og Title"><meta property="og:description" content="Og Description"><meta property="og:image" content=""></head></html>';


    public function testValidate() {
        $response = new HttpResponse();
        $response->setBody( $this->testString );

        $rule = new OgPropertyPresent();
        $rule->setHttpResponse( $response );
        $rule->set( 'requiredProperties', array( 'title', 'description' ) );

        $result = $rule->validate();
        $this->assertTrue( $result );
    }

    public function testValidateWithEmptyPropertyIsFalse() {
        $response = new HttpResponse();
        $response->setBody( $this->testString );

        $rule = new OgPropertyPresent();
        $rule->setHttpResponse( $response );
        $rule->set( 'requiredProperties', array( 'title', 'description', 'image' ) );

        $result = $rule->validate();
        $this->assertFalse( $result );
    }

    public function testValidateWithNotFoundPropertyIsFalse() {
        $response = new HttpResponse();
        $response->setBody( $this->testString );

        $rule = new OgPropertyPresent();
        $rule->setHttpResponse( $response );
        $rule->set( 'requiredProperties', array( 'title', 'description', 'image:secure_url' ) );

        $result = $rule->validate();
        $this->assertFalse( $result );
    }

}
