<?php
namespace Frickelbruder\Kickoff\Tests\Configuration;

use Frickelbruder\KickOff\Configuration\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase {

    public function testBase() {
        $configuration = new Configuration();
        $configuration->buildFromFile(__DIR__ . '/files/config.yml');
        $sections = $configuration->getSections();

        $this->assertCount(2, $sections);
        $this->assertArrayHasKey('main', $sections);
        $this->assertArrayHasKey('second', $sections);

    }

    public function testBaseSectionTargetUrlOverride() {
        $configuration = new Configuration();
        $configuration->buildFromFile(__DIR__ . '/files/config.yml');
        $sections = $configuration->getSections();

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
        $configuration = new Configuration();
        $configuration->buildFromFile(__DIR__ . '/files/config.yml');
        $sections = $configuration->getSections();

        $mainSection = $sections['main'];
        $rules = $mainSection->getRules();

        $this->assertCount(2, $rules);

        $this->assertArrayHasKey('HttpHeaderXSSProtection', $rules);
        $this->assertArrayHasKey('HttpHeaderExposePHP', $rules);

        $this->assertInstanceOf('\Frickelbruder\KickOff\Rules\HttpHeaderPresent', $rules['HttpHeaderXSSProtection']);
        $this->assertInstanceOf('\Frickelbruder\KickOff\Rules\HttpHeaderNotPresent', $rules['HttpHeaderExposePHP']);

        $secondSection = $sections['second'];
        $rules = $secondSection->getRules();

        $this->assertCount(1, $rules, 'Not matching rules count');

        $this->assertArrayHasKey('HttpHeaderExposePHP', $rules);

        $this->assertInstanceOf('\Frickelbruder\KickOff\Rules\HttpHeaderNotPresent', $rules['HttpHeaderExposePHP']);
    }

}
