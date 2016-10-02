<?php
namespace Frickelbruder\KickOff\Tests\Rules\ConfiguredRules;

use Frickelbruder\KickOff\App\KickOff;
use Frickelbruder\KickOff\Configuration\Configuration;
use Frickelbruder\KickOff\Http\HttpRequester;
use Frickelbruder\KickOff\Log\Logger;
use Frickelbruder\KickOff\Yaml\Yaml;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Response;

class DefaultConfiguredRuleTestBase extends \PHPUnit_Framework_TestCase {
    /**
     * @var KickOffProxy
     */
    protected $kickOff;

    /**
     * @var HttpRequester
     */
    protected $requester = null;

    /**
     * @var Logger
     */
    private $logger = null;

    protected $defaultHeaders = array();

    protected $defaultHeadersUnhappyPath = array();

    protected $happyHttpCode = 200;

    protected $unhappyHttpCode = 404;

    public function setUp() {
        $yaml = new Yaml();
        $config = new Configuration($yaml);
        $this->requester = new HttpRequester();

        $this->logger = new Logger();
        $this->kickOff = new KickOffProxy();
        $this->kickOff->setConfiguration($config);
        $this->kickOff->setHttpRequester($this->requester);
        $this->kickOff->setLogger($this->logger);

        $this->defaultHeaders = array(
            'X-XSS-Protection' => '1; mode=block',
            'X-UA-Compatible' => 'ie=edge',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'SAMEORIGIN',
            'Set-Cookie' => 'PHPSESSID=SESSION; path=/; expires=WHENEVER; secure; HttpOnly',
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains; preload',
            'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() + 2000000),
            'ETag' => '1212112'
        );



        $this->defaultHeadersUnhappyPath = array(
            'Set-Cookie' => 'PHPSESSID=SESSION; path=/; expires=WHENEVER;',
            'Expires' => gmdate('D, d M Y H:i:s \G\M\T', time() - 2000000),
            'X-Powered-By' => 'My cool Scripting language v1.2.3'
        );

    }


    protected function defaultValidateTest($testName) {
        $responses = array();
        $responses[] = new Response( $this->happyHttpCode, $this->defaultHeaders, 'test123' );
        $this->requester->setClient($this->setupClient($responses));

        $errorCount = $this->kickOff->indexProxy( __DIR__ . '/files/configuredRules.yml', $testName);

        $this->assertTrue($errorCount == 0);
    }


    public function defaultValidateErrorTest($testName) {

        $responses = array();
        $responses[] = new Response( $this->unhappyHttpCode, $this->defaultHeadersUnhappyPath, 'test123' );
        $this->requester->setClient($this->setupClient($responses));

        $errorCount = $this->kickOff->indexProxy( __DIR__ . '/files/configuredRules.yml', $testName);

        $this->assertTrue($errorCount > 0);
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