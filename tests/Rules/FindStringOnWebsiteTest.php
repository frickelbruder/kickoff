<?php
namespace Frickelbruder\KickOff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\FindStringOnWebsite;

class FindStringOnWebsiteTest extends \PHPUnit_Framework_TestCase {

    public function testValidate() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head></head><body>TextToFind</body></html>');

        $rule = new FindStringOnWebsite();
        $rule->set('stringToSearchFor', 'TextToFind');
        $rule->setHttpResponse($response);

        $this->assertTrue($rule->validate());
    }

    public function testValidatesTags() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head></head><body>Bla</body></html>');

        $rule = new FindStringOnWebsite();
        $rule->set('stringToSearchFor', '</body>');
        $rule->setHttpResponse($response);

        $this->assertTrue($rule->validate());
    }

    public function testValidateFalseWhenTextIsNotThere() {
        $response = new HttpResponse();
        $response->setBody('<!DOCTYPE html><html><head></head><body>Bla</body></html>');

        $rule = new FindStringOnWebsite();
        $rule->set('stringToSearchFor', 'I am not there');
        $rule->setHttpResponse($response);

        $this->assertFalse($rule->validate());
    }

    public function testValidateFalseOnEmptyBody() {
        $response = new HttpResponse();
        $response->setBody('');

        $rule = new FindStringOnWebsite();
        $rule->set('stringToSearchFor', 'I am not there');
        $rule->setHttpResponse($response);

        $this->assertFalse($rule->validate());
    }
}