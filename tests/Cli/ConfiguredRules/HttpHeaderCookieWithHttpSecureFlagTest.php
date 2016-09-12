<?php
namespace Frickelbruder\KickOff\Tests\Cli\ConfiguredRules;


class HttpHeaderCookieWithHttpSecureFlagTest extends DefaultConfiguredRuleTestBase {

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderCookieWithHttpSecureFlag');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderCookieWithHttpSecureFlag');
    }
}