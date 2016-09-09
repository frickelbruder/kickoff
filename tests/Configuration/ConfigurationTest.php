<?php
namespace Frickelbruder\Kickoff\Tests\Configuration;

use Frickelbruder\KickOff\Configuration\Configuration;
use Frickelbruder\KickOff\Yaml\Yaml;

class ConfigurationTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var Configuration
     */
    private $configuration;

    public function setUp() {
        $this->configuration = new Configuration( new Yaml());
        $this->configuration->build(__DIR__ . '/files/config.yml');
    }

    public function testBase() {


        $sections = $this->configuration->getSections();

        $this->assertCount(2, $sections);
        $this->assertArrayHasKey('main', $sections);
        $this->assertArrayHasKey('second', $sections);

    }

    public function testBaseSectionTargetUrlOverride() {
        $sections = $this->configuration->getSections();

        $mainSection = $sections['main'];
        $targetURL = $mainSection->getTargetUrl();

        $expected = 'https://test.host/';

        $this->assertEquals($expected, $targetURL->getUrl());

        $subSection = $sections['second'];
        $targetURL = $subSection->getTargetUrl();

        $expected = 'https://test2.host:8080/';

        $this->assertEquals($expected, $targetURL->getUrl());
    }

    public function testBaseSectionRules() {
        $sections = $this->configuration->getSections();

        $mainSection = $sections['main'];
        $rules = $mainSection->getRules();

        $this->assertCount(3, $rules);

        $this->assertArrayHasKey('HttpHeaderXSSProtection', $rules);
        $this->assertArrayHasKey('HttpHeaderExposePHP', $rules);
        $this->assertArrayHasKey('HttpRequestTime', $rules);

        $this->assertInstanceOf('\Frickelbruder\KickOff\Rules\HttpHeaderPresent', $rules['HttpHeaderXSSProtection']);
        $this->assertInstanceOf('\Frickelbruder\KickOff\Rules\HttpHeaderNotPresent', $rules['HttpHeaderExposePHP']);
        $this->assertInstanceOf('\Frickelbruder\KickOff\Rules\HttpRequestTime', $rules['HttpRequestTime']);

        $secondSection = $sections['second'];
        $rules = $secondSection->getRules();

        $this->assertCount(1, $rules, 'Not matching rules count');

        $this->assertArrayHasKey('HttpHeaderExposePHP', $rules);

        $this->assertInstanceOf('\Frickelbruder\KickOff\Rules\HttpHeaderNotPresent', $rules['HttpHeaderExposePHP']);
    }

    public function testConfigurationOfRule() {
        $sections = $this->configuration->getSections();

        $mainSection = $sections['main'];
        $rules = $mainSection->getRules();

        $configuredRule = $rules['HttpRequestTime'];
        $configuredRule->maxTransferTime = 22500;
    }

}
