<?php
namespace Frickelbruder\KickOff\Tests\Cli\ConfiguredRules;


class HttpHeaderFrameOptionsSameOriginTest extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderFrameOptionsSameOrigin');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderFrameOptionsSameOrigin');
    }
}