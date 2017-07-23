<?php

namespace Frickelbruder\KickOff\Tests\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\RobotsTxtDoesNotExcludeAll;

class RobotsTxtDoesNotExcludeAllTest extends \PHPUnit_Framework_TestCase
{

    public function testHappyPath()
    {

        $testString = <<<ROBOTSTXT
User-Agent: *
Disallow: /
ROBOTSTXT;

        $response = new HttpResponse();
        $response->setBody( $testString );

        $rule = new RobotsTxtDoesNotExcludeAll();
        $rule->setHttpResponse( $response );

        $this->assertFalse($rule->validate());
    }

    public function testDoesNotFindOnlySpecialExcludedUA()
    {

        $testString = <<<ROBOTSTXT
User-Agent: GoogleBot
Disallow: /
ROBOTSTXT;

        $response = new HttpResponse();
        $response->setBody( $testString );

        $rule = new RobotsTxtDoesNotExcludeAll();
        $rule->setHttpResponse( $response );

        $this->assertTrue($rule->validate());
    }

    public function testDoesNotMixWithDifferentUA()
    {

        $testString = <<<ROBOTSTXT
User-Agent: GoogleBot
Disallow: /
        
User-Agent: *
Disallow: /somesite
ROBOTSTXT;

        $response = new HttpResponse();
        $response->setBody( $testString );

        $rule = new RobotsTxtDoesNotExcludeAll();
        $rule->setHttpResponse( $response );

        $this->assertTrue($rule->validate());
    }

    public function testDoesNotMatchEmptyFile()
    {

        $testString = <<<ROBOTSTXT
ROBOTSTXT;

        $response = new HttpResponse();
        $response->setBody( $testString );

        $rule = new RobotsTxtDoesNotExcludeAll();
        $rule->setHttpResponse( $response );

        $this->assertTrue($rule->validate());
    }

}
