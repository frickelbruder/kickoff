<?php
namespace Frickelbruder\KickOff\App;

use Frickelbruder\KickOff\Configuration\Configuration;
use Frickelbruder\KickOff\Configuration\Section;
use Frickelbruder\KickOff\Configuration\TargetUrl;
use Frickelbruder\KickOff\Http\HttpRequester;
use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Log\Logger;
use Frickelbruder\KickOff\Rules\Interfaces\RuleInterface;

class KickOff {

    /**
     * @var Configuration
     */
    private $configuration = null;

    /**
     * @var int
     */
    protected $errorCount = 0;

    /**
     * @var HttpRequester
     */
    protected $httpRequester = null;

    /**
     * @var Logger
     */
    private $logger = null;

    /**
     * @param string $configFile
     * @param string $webpage
     *
     * @return int
     */
    public function index($configFile, $webpage = null) {
        $this->buildConfiguration( $configFile );

        foreach( $this->getSections() as $section ) {
            $this->handleSection( $section, $webpage );
        }

        $this->logger->finish();

        return $this->errorCount;
    }

    protected function buildConfiguration($configFile) {
        $this->configuration->build($configFile);
    }

    protected function getSections() {
        return $this->configuration->getSections();
    }

    /**
     * @param Section $section
     */
    protected function handleSection(Section $section, $targetUrl = null) {
        $url = $targetUrl ? new TargetUrl($targetUrl) : $section->getTargetUrl();

        $response = $this->fetchPage($url);
        $rules = $section->getRules();

        foreach($rules as $rule) {
            $this->handleRule( $section, $rule, $response );
        }
    }


    /**
     * @param Section $section
     * @param RuleInterface $rule
     * @param $response
     */
    protected function handleRule(Section $section, RuleInterface $rule, $response) {
        $rule->setHttpResponse( $response );
        $result = $rule->validate();
        if( !$result ) {
            $this->errorCount++;
        }

        $this->logger->log( $section->getName(), $section->getTargetUrl()->getUrl(), $rule, $result );
    }


    /**
     * @param TargetUrl $url
     *
     * @return HttpResponse
     */
    protected function fetchPage(TargetUrl $url) {
        return $this->httpRequester->request($url);
    }

    /**
     * @param Configuration $configuration
     */
    public function setConfiguration($configuration) {
        $this->configuration = $configuration;
    }

    /**
     * @param HttpRequester $httpRequester
     */
    public function setHttpRequester($httpRequester) {
        $this->httpRequester = $httpRequester;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger($logger) {
        $this->logger = $logger;
    }

}