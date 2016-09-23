<?php
namespace Frickelbruder\KickOff\Tests\Rules\ConfiguredRules;


class HttpHeaderFrameOptionsSameOriginTest extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderFrameOptionsSameOrigin');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderFrameOptionsSameOrigin');
    }
}