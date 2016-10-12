<?php

namespace Frickelbruder\KickOff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\ValidSslCertificate;

class ValidSslCertificateTest extends \PHPUnit_Framework_TestCase
{

    public function testAcceptsValidCertificate()
    {
        $rule = new ValidSslCertificate();
        $rule->setHttpResponse(new HttpResponse());

        $this->assertTrue($rule->validate());
    }


    public function testDetectsInvalidCertificate()
    {
        $errorMessage = 'self signed certificate';

        $response = new HttpResponse();
        $response->setSslCertificateError($errorMessage);

        $rule = new ValidSslCertificate();
        $rule->setHttpResponse($response);

        $this->assertFalse($rule->validate());
        $this->assertEquals($errorMessage, $rule->getErrorMessage());
    }
}
