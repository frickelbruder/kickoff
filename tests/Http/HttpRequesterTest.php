<?php
namespace Frickelbruder\KickOff\Tests\Http;

use Frickelbruder\KickOff\Configuration\TargetUrl;
use Frickelbruder\KickOff\Http\HttpRequester;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

class HttpRequesterTest extends \PHPUnit_Framework_TestCase {

    public function testRequest() {
        $targetUrl = new TargetUrl();
        $targetUrl->host='test.host';
        $targetUrl->addHeader('User-Agent', 'none');

        $responses = array();
        $responses[] = new Response( 200, [ 'X-Test' => 'Bar' ], 'test123' );

        $client = $this->setupClient($responses);

        $requester = new HttpRequester();
        $requester->setClient($client);
        $result = $requester->request($targetUrl);

        $this->assertInstanceOf('\Frickelbruder\KickOff\Http\HttpResponse', $result);
        $this->assertEquals(200, $result->getStatus());
        $this->assertArrayHasKey('X-Test', $result->getHeaders());
        $this->assertEquals('test123', $result->getBody());

    }

    public function testRequestUsesCache() {
        $responses = array();
        $responses[] = new Response(200, ['X-Test' => 'Bar'], 'test123');
        $responses[] = new Response(201, ['X-Repeated' => 'Bar'], 'test123');

        $client = $this->setupClient($responses);

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

    public function testRequestSendDefaultGzipHeader() {
        $responses = array();
        $responses[] = new Response(200, ['X-Test' => 'Bar'], 'test123');

        $historyContainer = array();
        $client = $this->setupClient($responses, $historyContainer);

        $targetUrl = new TargetUrl();
        $targetUrl->host='test.host';
        $targetUrl->addHeader('User-Agent', 'none');

        $requester = new HttpRequester();
        $requester->setClient($client);
        $result = $requester->request($targetUrl);

        $this->assertInstanceOf('\Frickelbruder\KickOff\Http\HttpResponse', $result);

        $firstRequest = $historyContainer[0]['request'];
        /* @var $firstRequest \GuzzleHttp\Psr7\Request */
        $value = $firstRequest->getHeaders();

        $this->assertCount(2, $value); //User-Agent and host
    }

    public function testRequestSendCustomHeader() {
        $responses = array();
        $responses[] = new Response(200, ['X-Test' => 'Bar'], 'test123');

        $historyContainer = array();
        $client = $this->setupClient($responses, $historyContainer);

        $targetUrl = new TargetUrl();
        $targetUrl->host='test.host';
        $targetUrl->headers = array('X-Test'=>'TestHeaderValue1', 'X-Test2'=>'TestHeaderValue2');

        $requester = new HttpRequester();
        $requester->setClient($client);
        $result = $requester->request($targetUrl);

        $this->assertInstanceOf('\Frickelbruder\KickOff\Http\HttpResponse', $result);

        $firstRequest = $historyContainer[0]['request'];
        /* @var $firstRequest \GuzzleHttp\Psr7\Request */
        $this->assertTrue($firstRequest->hasHeader('X-Test'));
        $this->assertTrue($firstRequest->hasHeader('X-Test2'));

        $this->assertEquals( array($targetUrl->headers['X-Test']), $firstRequest->getHeader('X-Test'));
        $this->assertEquals( array($targetUrl->headers['X-Test2']), $firstRequest->getHeader('X-Test2'));

    }

    /**
     * @return Client
     */
    public function setupClient($responses = array(), &$historyContainer = null) {

        $mock = new MockHandler($responses);

        $handler = HandlerStack::create( $mock );

        if(!is_null($historyContainer)) {
            $history = Middleware::history($historyContainer);
            $handler->push($history);
        }

        $client = new Client( array( 'handler' => $handler ) );

        return $client;
    }


}
