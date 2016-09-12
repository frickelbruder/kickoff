<?php
namespace Frickelbruder\KickOff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\HttpHeaderHasValue;

class HttpHeaderHasValueTest extends \PHPUnit_Framework_TestCase {

    public function testValidate() {
        $response = new HttpResponse();
        $response->setHeaders(array('X-Test' => 'Valid'));

        $rule = new HttpHeaderHasValue();
        $rule->setHttpResponse($response);
        $rule->setHeaderToSearchFor('X-Test');
        $rule->setValue('Valid');

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateDoesNotFindValueWithExistingHeader() {
        $response = new HttpResponse();
        $response->setHeaders(array('X-Test' => 'Valid'));

        $rule = new HttpHeaderHasValue();
        $rule->setHttpResponse($response);
        $rule->setHeaderToSearchFor('X-Test');
        $rule->setValue('Invalid');

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateDoesNotFindValueWithMissingHeader() {
        $response = new HttpResponse();
        $response->setHeaders(array('X-Test' => 'Valid'));

        $rule = new HttpHeaderHasValue();
        $rule->setHttpResponse($response);
        $rule->setHeaderToSearchFor('X-Not-Found');
        $rule->setValue('Invalid');

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateFindsValueCIWithExactMatch() {
        $response = new HttpResponse();
        $response->setHeaders(array('X-Test' => 'Valid'));

        $rule = new HttpHeaderHasValue();
        $rule->setHttpResponse($response);
        $rule->setHeaderToSearchFor('X-Test');
        $rule->setValue('vAlId');

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateFindsValueCIWithoutExactMatch() {
        $response = new HttpResponse();
        $response->setHeaders(array('X-Test' => 'Valid'));

        $rule = new HttpHeaderHasValue();
        $rule->setHttpResponse($response);
        $rule->setHeaderToSearchFor('X-Test');
        $rule->setExactMatch(false);
        $rule->setValue('vAlId');

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateDoesNotFindValueInPartialMatch() {
        $response = new HttpResponse();
        $response->setHeaders(array('X-Test' => 'Some unimportant Header'));

        $rule = new HttpHeaderHasValue();
        $rule->setHttpResponse($response);
        $rule->setHeaderToSearchFor('X-Test');
        $rule->setValue('important Header');

        $result = $rule->validate();
        $this->assertFalse($result);
    }

    public function testValidateFindsValueInPartialMatch() {
        $response = new HttpResponse();
        $response->setHeaders(array('X-Test' => 'Some unimportant Header'));

        $rule = new HttpHeaderHasValue();
        $rule->setHttpResponse($response);
        $rule->setHeaderToSearchFor('X-Test');
        $rule->setExactMatch(false);
        $rule->setValue('important Header');

        $result = $rule->validate();
        $this->assertTrue($result);
    }

    public function testValidateFindsPartialValue() {
        $response = new HttpResponse();
        $response->setHeaders(array('X-Test' => 'important Header'));

        $rule = new HttpHeaderHasValue();
        $rule->setHttpResponse($response);
        $rule->setHeaderToSearchFor('X-Test');
        $rule->setExactMatch(false);
        $rule->setValue('Some unimportant Header');

        $result = $rule->validate();
        $this->assertTrue($result);
    }
}
