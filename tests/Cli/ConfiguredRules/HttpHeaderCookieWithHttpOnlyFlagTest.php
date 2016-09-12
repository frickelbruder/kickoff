<?php
namespace Frickelbruder\KickOff\Tests\Cli\ConfiguredRules;


class HttpHeaderCookieWithHttpOnlyFlagTest extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderCookieWithHttpOnlyFlag');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderCookieWithHttpOnlyFlag');
    }
}