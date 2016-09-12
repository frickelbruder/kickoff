<?php
namespace Frickelbruder\KickOff\Tests\Cli\ConfiguredRules;


class HttpHeaderHSTSPresentTest extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderHSTSPresent');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderHSTSPresent');
    }
}