<?php
namespace Frickelbruder\KickOff\Tests\Cli\ConfiguredRules;


class HttpHeaderHasEtagTest extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderHasEtag');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderHasEtag');
    }
}