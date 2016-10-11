<?php

namespace Frickelbruder\KickOff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\H1TagPresent;

class H1TagPresentTest extends \PHPUnit_Framework_TestCase
{

    const NO_H1_TAG = '<!DOCTYPE html><html><body><p>Foo</p></body></html>';

    const ONE_H1_TAG = '<!DOCTYPE html><html><body><h1>Foo</h1><p>Bar</p></body></html>';

    const TWO_H1_TAGS = '<!DOCTYPE html><html><body><h1>Foo</h1><p>Bar</p><h1>Lorem</h1><p>Ipsum</p></body></html>';


    public function testDetectsMissingH1Tag()
    {
        $results = $this->validateString(self::NO_H1_TAG);

        $this->assertFalse($results['result']);
        $this->assertFalse($results['strictResult']);
    }


    public function testDetectsMultipleH1Tags()
    {
        $results = $this->validateString(self::TWO_H1_TAGS);

        $this->assertTrue($results['result']);
        $this->assertFalse($results['strictResult']);
    }


    public function testSucceedsWhenExactlyOneH1TagExists()
    {
        $results = $this->validateString(self::ONE_H1_TAG);

        $this->assertTrue($results['result']);
        $this->assertTrue($results['strictResult']);
    }


    private function validateString($testString)
    {
        $response = new HttpResponse();
        $response->setBody($testString);

        $rule = new H1TagPresent();
        $rule->setHttpResponse($response);

        $strictRule = new H1TagPresent();
        $strictRule->setHttpResponse($response);
        $strictRule->allowMultipleTags(false);

        return [
            'result'       => $rule->validate(),
            'strictResult' => $strictRule->validate(),
        ];
    }
}
