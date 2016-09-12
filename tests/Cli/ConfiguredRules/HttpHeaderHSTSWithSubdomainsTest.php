<?php
namespace Frickelbruder\KickOff\Tests\Cli\ConfiguredRules;


class HttpHeaderHSTSWithSubdomainsTest extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderHSTSWithSubdomains');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderHSTSWithSubdomains');
    }
}