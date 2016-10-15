<?php
namespace Frickelbruder\KickOff\Tests\Configuration;

use Frickelbruder\KickOff\Configuration\TargetUrl;

class TargetUrlTest extends \PHPUnit_Framework_TestCase {

    public function testGetUrl() {
        $scheme = 'httpd';
        $host = 'www.test.frickel';
        $port = '123';
        $uri = 'asdfsdfasdfasdfasdfasdfasd';
        $expected = $scheme . '://' . $host . ':' . $port . '/' . $uri;

        $targetUrl = new TargetUrl();
        $targetUrl->host = $host;
        $targetUrl->port = $port;
        $targetUrl->path = $uri;
        $targetUrl->scheme = $scheme;

        $this->assertEquals($expected, $targetUrl->getUrl());
    }

    public function testGetUrlWithoutPort() {
        $scheme = 'httpd';
        $host = 'www.test.frickel';
        $uri = 'asdfsdfasdfasdfasdfasdfasd';
        $expected = $scheme . '://' . $host . '/' . $uri;

        $targetUrl = new TargetUrl();
        $targetUrl->host = $host;
        $targetUrl->path = $uri;
        $targetUrl->scheme = $scheme;

        $this->assertEquals($expected, $targetUrl->getUrl());
    }

    public function testGetUrlWithoutGivenScheme() {

        $host = 'www.test.frickel';
        $port = '123';
        $uri = 'asdfsdfasdfasdfasdfasdfasd';
        $expected = 'http://' . $host . ':' . $port . '/' . $uri;

        $targetUrl = new TargetUrl();
        $targetUrl->host = $host;
        $targetUrl->port = $port;
        $targetUrl->path = $uri;

        $this->assertEquals($expected, $targetUrl->getUrl());
    }

    public function testGetUrlWithoutUri() {
        $scheme = 'httpd';
        $host = 'www.test.frickel';
        $port = '123';
        $expected = $scheme . '://' . $host . ':' . $port . '/' ;

        $targetUrl = new TargetUrl();
        $targetUrl->host = $host;
        $targetUrl->port = $port;
        $targetUrl->scheme = $scheme;

        $this->assertEquals($expected, $targetUrl->getUrl());
    }

    public function wellformedUrlProvider() {
        return [
            ['google.com', 'http://google.com/'],
            ['google.com/somepath', 'http://google.com/somepath'],
            ['www.google.com', 'http://www.google.com/'],
            ['http://www.google.com', 'http://www.google.com/'],
            ['http://www.google.com/somepath', 'http://www.google.com/somepath']
        ];
    }

    /**
     * @dataProvider wellformedUrlProvider
     *
     * @param $input
     * @param $output
     */
    public function testFromString($input, $output) {
        $targetUrl = new TargetUrl($input);

        $this->assertEquals($output, $targetUrl->getUrl());

    }


}
