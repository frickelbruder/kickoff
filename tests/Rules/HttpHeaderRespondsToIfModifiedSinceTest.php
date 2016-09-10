<?php
namespace Frickelbruder\Kickoff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\HttpHeaderRespondsToIfModifiedSince;

class HttpHeaderRespondsToIfModifiedSinceTest extends \PHPUnit_Framework_TestCase {

    public function testValidate() {
        $response = new HttpResponse();
        $response->setStatus( 304 );

        $rule = new HttpHeaderRespondsToIfModifiedSince();
        $rule->setHttpResponse( $response );

        $headers = $rule->getRequiredHeaders();

        $this->assertCount(1, $headers);
        $this->assertTrue(strtolower($headers[0][0]) == 'if-modified-since');

        $result = $rule->validate();
        $this->assertTrue( $result );
    }

    public function testValidateWithStatusCode200() {
        $response = new HttpResponse();
        $response->setStatus( 200 );

        $rule = new HttpHeaderRespondsToIfModifiedSince();
        $rule->setHttpResponse( $response );

        $result = $rule->validate();
        $this->assertFalse( $result );
    }

    public function testValidateWithAnyOtherStatusCodeNot304() {
        $response = new HttpResponse();
        $response->setStatus( 122 );

        $rule = new HttpHeaderRespondsToIfModifiedSince();
        $rule->setHttpResponse( $response );

        $result = $rule->validate();
        $this->assertFalse( $result );
    }

}
