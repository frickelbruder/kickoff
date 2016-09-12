<?php
namespace Frickelbruder\KickOff\Tests\Cli;

use Frickelbruder\KickOff\Configuration\Configuration;
use Frickelbruder\KickOff\Http\HttpRequester;
use Frickelbruder\KickOff\Log\Listener\ConsoleOutputListener;
use Frickelbruder\KickOff\Log\Logger;
use Frickelbruder\KickOff\Yaml\Yaml;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

class ConfiguredRuleDefaultCommandTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var DefaultCommandProxy
     */
    private $defaultCommand;

    /**
     * @var HttpRequester
     */
    private $requester = null;

    /**
     * @var Logger
     */
    private $logger = null;

    private $defaultHeaders = array();

    public function setUp() {
        $yaml = new Yaml();
        $config = new Configuration($yaml);
        $this->requester = new HttpRequester();

        $this->logger = new Logger();
        $this->logger->addListener('log', new ConsoleOutputListener());
        $this->defaultCommand = new DefaultCommandProxy('test', $this->requester, $config, $this->logger);

        $this->defaultHeaders = array(
            'X-XSS-Protection' => '1; mode=block',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Set-Cookie' => 'PHPSESSID=SESSION; path=/; expires=WHENEVER; secure; HttpOnly',
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
            'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + 2000000),
            'ETag' => '1212112'
        );

    }

    public function testHttpHeaderXSSProtectionPresent() {
        $responses = array();
        $responses[] = new Response( 200, $this->defaultHeaders, 'test123' );
        $this->requester->setClient($this->setupClient($responses));

        $errorCount = $this->defaultCommand->executeProxy(__DIR__ . '/files/configuredRules.yml', 'HttpHeaderXSSProtectionPresent');

        $this->assertTrue($errorCount == 0);
    }

    public function testHttpHeaderXSSProtectionSecure() {
        $responses = array();
        $responses[] = new Response( 200, $this->defaultHeaders, 'test123' );
        $this->requester->setClient($this->setupClient($responses));

        $errorCount = $this->defaultCommand->executeProxy(__DIR__ . '/files/configuredRules.yml', 'HttpHeaderXSSProtectionSecure');

        $this->assertTrue($errorCount == 0);
    }
    public function testHttpHeaderExposeLanguage() {
        $responses = array();
        $responses[] = new Response( 200, $this->defaultHeaders, 'test123' );
        $this->requester->setClient($this->setupClient($responses));

        $errorCount = $this->defaultCommand->executeProxy(__DIR__ . '/files/configuredRules.yml', 'HttpHeaderExposeLanguage');

        $this->assertTrue($errorCount == 0);
    }

    public function testHttpHeaderHasEtag() {
        $responses = array();
        $responses[] = new Response( 200, $this->defaultHeaders, 'test123' );
        $this->requester->setClient($this->setupClient($responses));

        $errorCount = $this->defaultCommand->executeProxy(__DIR__ . '/files/configuredRules.yml', 'HttpHeaderHasEtag');

        $this->assertTrue($errorCount == 0);
    }
    public function testHttpHeaderResourceFound() {
        $responses = array();
        $responses[] = new Response( 200, $this->defaultHeaders, 'test123' );
        $this->requester->setClient($this->setupClient($responses));

        $errorCount = $this->defaultCommand->executeProxy(__DIR__ . '/files/configuredRules.yml', 'HttpHeaderResourceFound');

        $this->assertTrue($errorCount == 0);
    }
    public function testHttpHeaderResourceIsMissing() {
        $responses = array();
        $responses[] = new Response( 404, $this->defaultHeaders, 'test123' );
        $this->requester->setClient($this->setupClient($responses));

        $errorCount = $this->defaultCommand->executeProxy(__DIR__ . '/files/configuredRules.yml', 'HttpHeaderResourceIsMissing');

        $this->assertTrue($errorCount == 0);
    }
    public function testHttpHeaderContentTypeNoSniffing() {
        $responses = array();
        $responses[] = new Response( 200, $this->defaultHeaders, 'test123' );
        $this->requester->setClient($this->setupClient($responses));

        $errorCount = $this->defaultCommand->executeProxy(__DIR__ . '/files/configuredRules.yml', 'HttpHeaderContentTypeNoSniffing');

        $this->assertTrue($errorCount == 0);
    }
    public function testHttpHeaderFrameOptionsSameOrigin() {
        $responses = array();
        $responses[] = new Response( 200, $this->defaultHeaders, 'test123' );
        $this->requester->setClient($this->setupClient($responses));

        $errorCount = $this->defaultCommand->executeProxy(__DIR__ . '/files/configuredRules.yml', 'HttpHeaderFrameOptionsSameOrigin');

        $this->assertTrue($errorCount == 0);
    }
    public function testHttpHeaderCookieWithHttpOnlyFlag() {
        $responses = array();
        $responses[] = new Response( 200, $this->defaultHeaders, 'test123' );
        $this->requester->setClient($this->setupClient($responses));

        $errorCount = $this->defaultCommand->executeProxy(__DIR__ . '/files/configuredRules.yml', 'HttpHeaderCookieWithHttpOnlyFlag');

        $this->assertTrue($errorCount == 0);
    }
    public function testHttpHeaderCookieWithHttpSecureFlag() {
        $responses = array();
        $responses[] = new Response( 200, $this->defaultHeaders, 'test123' );
        $this->requester->setClient($this->setupClient($responses));

        $errorCount = $this->defaultCommand->executeProxy(__DIR__ . '/files/configuredRules.yml', 'HttpHeaderCookieWithHttpSecureFlag');

        $this->assertTrue($errorCount == 0);
    }
    public function testHttpHeaderHSTSPresent() {
        $responses = array();
        $responses[] = new Response( 200, $this->defaultHeaders, 'test123' );
        $this->requester->setClient($this->setupClient($responses));

        $errorCount = $this->defaultCommand->executeProxy(__DIR__ . '/files/configuredRules.yml', 'HttpHeaderHSTSPresent');

        $this->assertTrue($errorCount == 0);
    }
    public function testHttpHeaderHSTSWithSubdomains() {
        $responses = array();
        $responses[] = new Response( 200, $this->defaultHeaders, 'test123' );
        $this->requester->setClient($this->setupClient($responses));

        $errorCount = $this->defaultCommand->executeProxy(__DIR__ . '/files/configuredRules.yml', 'HttpHeaderHSTSWithSubdomains');

        $this->assertTrue($errorCount == 0);
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