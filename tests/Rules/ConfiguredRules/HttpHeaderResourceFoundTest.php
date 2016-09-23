<?php
namespace Frickelbruder\KickOff\Tests\Rules\ConfiguredRules;


class HttpHeaderResourceFoundTest extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderResourceFound');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderResourceFound');
    }
}