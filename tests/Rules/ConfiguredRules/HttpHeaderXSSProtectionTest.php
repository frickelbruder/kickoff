<?php
namespace Frickelbruder\KickOff\Tests\Rules\ConfiguredRules;

class HttpHeaderXSSProtectionTestBase extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderXSSProtectionPresent');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderXSSProtectionPresent');
    }
}