<?php
namespace Frickelbruder\KickOff\Tests\Rules\ConfiguredRules;


class HttpHeaderHSTSPresentTest extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderHSTSPresent');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderHSTSPresent');
    }
}