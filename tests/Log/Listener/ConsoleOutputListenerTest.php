<?php

namespace Frickelbruder\KickOff\Tests\Log\Listener;

use Frickelbruder\KickOff\Log\Listener\ConsoleOutputListener;
use Frickelbruder\KickOff\Tests\Log\RuleStub;
use Frickelbruder\KickOff\Tests\TestHelper\Console\StringOutput;

class ConsoleOutputListenerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var ConsoleOutputListener
     */
    private $listener;

    /** @var StringOutput */
    private $consoleOutput;

    protected function setup() {
        $this->consoleOutput = new StringOutput();
        $this->listener = new ConsoleOutputListener($this->consoleOutput);
    }

    public function testClass() {
        $rule = new RuleStub();
        $rule->name='Testcase1';
        $rule2 = new RuleStub();
        $rule2->name='Testcase2';
        $rule3 = new RuleStub();
        $rule3->name='Testcase3';

        $this->listener->log('Section1', 'http://www.test1.com', $rule, true);
        $this->listener->log('Section1', 'http://www.test1.com', $rule2, false);
        $this->listener->log('Section2', 'http://www.test2.com', $rule3, true);

        $this->listener->finish();

        $expectedOutput = file_get_contents(__DIR__ . '/files/console.txt');
        $this->assertEquals($this->normalizeEOL($expectedOutput), $this->normalizeEOL($this->consoleOutput->getOutput()));
    }

    private function normalizeEOL($string) {
        return str_replace("\r\n", "\n", $string);
    }
}
