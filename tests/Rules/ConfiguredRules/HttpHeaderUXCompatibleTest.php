<?php
namespace Frickelbruder\KickOff\Tests\Rules\ConfiguredRules;

class HttpHeaderUXCompatibleTestBase extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderUXCompatible');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderUXCompatible');
    }
}