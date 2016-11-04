<?php
namespace Frickelbruder\KickOff\Tests\Rules\ConfiguredRules;


class HttpHeaderResourceMovedPermanentlyTest extends DefaultConfiguredRuleTestBase {

    protected $happyHttpCode = 301;

    protected $unhappyHttpCode = 200;

    public function testValidate() {
        $this->defaultValidateTest('HttpHeaderResourceMovedPermanently');
    }

    public function testValidateError() {
        $this->defaultValidateErrorTest('HttpHeaderResourceMovedPermanently');
    }
}