<?php
namespace Frickelbruder\KickOff\Tests\Rules\ConfiguredRules;


class HttpHeaderXSSProtectionSecureTestBase extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderXSSProtectionSecure');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderXSSProtectionSecure');
    }
}