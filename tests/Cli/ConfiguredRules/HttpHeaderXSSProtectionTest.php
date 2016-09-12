<?php
namespace Frickelbruder\KickOff\Tests\Cli\ConfiguredRules;

class HttpHeaderXSSProtectionTestBase extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderXSSProtectionPresent');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderXSSProtectionPresent');
    }
}