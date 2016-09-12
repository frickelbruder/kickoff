<?php
namespace Frickelbruder\KickOff\Tests\Cli\ConfiguredRules;


class HttpHeaderContentTypeNoSniffingTest extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderContentTypeNoSniffing');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderContentTypeNoSniffing');
    }
}