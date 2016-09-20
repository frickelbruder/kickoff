<?php
namespace Frickelbruder\KickOff\Tests\Rules\ConfiguredRules;


class HttpHeaderResourceIsMissingTest extends DefaultConfiguredRuleTestBase {

    protected $happyHttpCode = 404;

    protected $unhappyHttpCode = 200;

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderResourceIsMissing');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderResourceIsMissing');
    }
}