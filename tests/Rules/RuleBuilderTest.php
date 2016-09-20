<?php
namespace Frickelbruder\KickOff\Tests\Rules;

use Frickelbruder\KickOff\Configuration\RuleBuilder;

class RuleBuilderTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var RuleBuilder
     */
    private $ruleBuilder;

    private $defaultRules = array(
        'TestRule1' => array(
            'class' => '\Frickelbruder\KickOff\Rules\HttpHeaderNotPresent'
        ),
        'TestRule2' => array(
            'class' => '\Frickelbruder\KickOff\Rules\HttpRequestTime',
            'configuration' => array(array('set', array('maxTransferTime', 3000)))
        ),
        'TestRule3' => array(
            'class' => '\Frickelbruder\KickOff\Tests\Rules\RuleTestDummy',
            'configuration' => array(array('set', array('field2', 3000))),
            'calls' => array(
                array('setField1', array(false))
            )
        ),
    );


    public function setup() {
        $this->ruleBuilder = new RuleBuilder();
    }

    public function testBuildRules() {

        $rules = $this->ruleBuilder->buildRules($this->defaultRules);
        $this->assertCount(3, $rules);

        $this->assertArrayHasKey('TestRule1', $rules);
        $this->assertArrayHasKey('TestRule2', $rules);
        $this->assertArrayHasKey('TestRule3', $rules);

        $rule2 = $rules['TestRule2'];
        $rule3 = $rules['TestRule3'];

        $this->assertEquals(3000, $rule2->maxTransferTime);
        $this->assertEquals(false, $rule3->field1);
        $this->assertEquals(3000, $rule3->field2);

    }

    public function testBuildRulesThrowsExceptionOnConfiguringUnconfigurableRule() {
        $this->setExpectedException('\Frickelbruder\KickOff\Rules\Exceptions\RuleNotConfigurableException');
        $this->defaultRules['TestRule1']['configuration'] = array(array('set', array('headerToSearchFor', 'AnyHeader')));
        $this->ruleBuilder->buildRules($this->defaultRules);
    }


}
