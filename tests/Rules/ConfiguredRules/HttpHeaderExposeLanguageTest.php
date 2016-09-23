<?php
namespace Frickelbruder\KickOff\Tests\Rules\ConfiguredRules;


class HttpHeaderExposeLanguageTest extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderExposeLanguage');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderExposeLanguage');
    }
}