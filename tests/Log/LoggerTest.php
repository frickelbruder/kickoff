<?php
namespace Frickelbruder\KickOff\Tests\Log;

use Frickelbruder\KickOff\Log\Logger;

class LoggerTest extends \PHPUnit_Framework_TestCase {

    public function testAddRemoveGetListener() {
        $stub = new LogListenerStub();
        $logger = new Logger();

        $logger->addListener('test', $stub);

        $listener = $logger->getListener('test');

        $this->assertInstanceOf('\Frickelbruder\KickOff\Tests\Log\LogListenerStub', $listener);

        $logger->removeListener('test');

        $this->setExpectedException(
            '\Frickelbruder\KickOff\Log\Exceptions\ListenerNotFoundException'
        );
        $logger->getListener('test');

    }

    public function testLog() {
        $logStub = new LogListenerStub();
        $rule = new RuleStub();
        $logger = new Logger();
        $validationResult = true;
        $resultedLog = array('testsection' => array($rule->getName() => $validationResult));

        $logger->addListener('test', $logStub);
        $logger->log('testsection', 'url', $rule, $validationResult);

        $this->assertEquals($resultedLog, $logStub->logs);
    }

    public function testFinish() {
        $logStub = new LogListenerStub();
        $logger = new Logger();

        $logger->addListener('test', $logStub);
        $logger->finish();

        $this->assertTrue($logStub->finishCalled);
    }

    public function testLogSuccess() {
        $logStub = new LogListenerStub();
        $rule = new RuleStub();
        $logger = new Logger();
        $validationResult = true;
        $resultedLog = array('testsection' => array($rule->getName() => $validationResult));

        $logger->addListener('test', $logStub);
        $logger->logSuccess('testsection', 'url', $rule);

        $this->assertEquals($resultedLog, $logStub->logs);
    }

    public function testLogFail() {
        $logStub = new LogListenerStub();
        $rule = new RuleStub();
        $logger = new Logger();
        $validationResult = false;
        $resultedLog = array('testsection' => array($rule->getName() => $validationResult));

        $logger->addListener('test', $logStub);
        $logger->logFail('testsection', 'url', $rule);

        $this->assertEquals($resultedLog, $logStub->logs);
    }

}
