<?php
namespace Frickelbruder\KickOff\Test\Log\Listener;

use Frickelbruder\KickOff\Log\Listener\JunitLogListener;
use Frickelbruder\KickOff\Tests\Log\RuleStub;

class JunitLogListenerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var JunitLogListener
     */
    private $listener = null;

    protected function setup() {
        $this->listener = new JunitLogListener();
        $this->listener->logFileName = __DIR__ . '/files/' . uniqid('log_') . '.xml';
    }

    protected function tearDown() {
        if(is_file($this->listener->logFileName)) {
            unlink($this->listener->logFileName);
        }
    }

    public function testClass() {
        $rule = new RuleStub();
        $rule->name='Testcase1';
        $rule2 = new RuleStub();
        $rule2->name='Testcase2';
        $rule3 = new RuleStub();
        $rule3->name='Testcase3';

        $this->listener->log('Section1', '', $rule, true);
        $this->listener->log('Section1', '', $rule2, false);
        $this->listener->log('Section2', '', $rule3, true);

        $this->listener->finish();

        $this->assertFileExists($this->listener->logFileName);

        $this->assertXmlFileEqualsXmlFile($this->listener->logFileName, __DIR__ . '/files/main.xml');

    }

}
