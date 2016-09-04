<?php
namespace Frickelbruder\Kickoff\Tests\Http;

use Frickelbruder\KickOff\Configuration\TargetUrl;
use Frickelbruder\KickOff\Http\HttpRequester;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;

class HttpRequesterTest extends \PHPUnit_Framework_TestCase {

    public function testRequest() {
        $targetUrl = new TargetUrl();
        $targetUrl->host='test.host';

        $mock = new MockHandler(array(
            new Response(200, ['X-Test' => 'Bar'], 'test123')
          )
        );

        $handler = HandlerStack::create($mock);
        $client = new Client(array('handler' => $handler));

        $requester = new HttpRequester();
        $requester->setClient($client);
        $result = $requester->request($targetUrl);

        $this->assertInstanceOf('\Frickelbruder\KickOff\Http\HttpResponse', $result);
        $this->assertEquals(200, $result->getStatus());
        $this->assertArrayHasKey('X-Test', $result->getHeaders());
        $this->assertEquals('test123', $result->getBody());

    }

    public function testRequestUsesCache() {


        $mock = new MockHandler(array(
                new Response(200, ['X-Test' => 'Bar'], 'test123'),
                new Response(201, ['X-Repeated' => 'Bar'], 'test123')
            )
        );

        $handler = HandlerStack::create($mock);
        $client = new Client(array('handler' => $handler));

        $requester = new HttpRequester();
        $requester->setClient($client);

        $targetUrl = new TargetUrl();
        $targetUrl->host='test.host';
        $result = $requester->request($targetUrl);
        $this->assertArrayHasKey('X-Test', $result->getHeaders());

        $targetUrl = new TargetUrl();
        $targetUrl->host='test2.host';
        $result = $requester->request($targetUrl);
        $this->assertArrayHasKey('X-Repeated', $result->getHeaders());

        $targetUrl = new TargetUrl();
        $targetUrl->host='test.host';
        $result = $requester->request($targetUrl);
        $this->assertArrayHasKey('X-Test', $result->getHeaders());

    }



}
