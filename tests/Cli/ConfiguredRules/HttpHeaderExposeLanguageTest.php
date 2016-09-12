<?php
namespace Frickelbruder\KickOff\Tests\Cli\ConfiguredRules;


class HttpHeaderExposeLanguageTest extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderExposeLanguage');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderExposeLanguage');
    }
}