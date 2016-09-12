<?php
namespace Frickelbruder\KickOff\Tests\Cli\ConfiguredRules;


class HttpHeaderXSSProtectionSecureTestBase extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderXSSProtectionSecure');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderXSSProtectionSecure');
    }
}