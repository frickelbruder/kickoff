<?php
namespace Frickelbruder\KickOff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\HttpHeaderResourceIsGzipped;

class HttpHeaderResourceIsGzippedTest extends \PHPUnit_Framework_TestCase {

    public function testValidate() {
        $response = new HttpResponse();
        $response->setHeaders( array('Content-Encoding' => 'gzip') );

        $rule = new HttpHeaderResourceIsGzipped();
        $rule->setHttpResponse( $response );

        $headers = $rule->getRequiredHeaders();

        $this->assertCount(1, $headers);
        $this->assertTrue(strtolower($headers[0][0]) == 'accept-encoding');

        $result = $rule->validate();
        $this->assertTrue( $result );
    }

    public function testValidateIsOnlyValidWithGzip() {
        $response = new HttpResponse();
        $response->setHeaders( array('Content-Encoding' => 'deflate') );

        $rule = new HttpHeaderResourceIsGzipped();
        $rule->setHttpResponse( $response );

        $result = $rule->validate();
        $this->assertFalse( $result );
    }

    public function testValidateRequiresContentEncodingHeader() {
        $response = new HttpResponse();
        $response->setHeaders( array('Anything' => 'gzip') );

        $rule = new HttpHeaderResourceIsGzipped();
        $rule->setHttpResponse( $response );

        $result = $rule->validate();
        $this->assertFalse( $result );
    }
}
