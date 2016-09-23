<?php
namespace Frickelbruder\KickOff\Tests\Rules\ConfiguredRules;


class HttpHeaderHSTSWithSubdomainsTest extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderHSTSWithSubdomains');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderHSTSWithSubdomains');
    }
}