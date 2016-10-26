<?php
namespace Frickelbruder\KickOff\Tests\Log\Listener;

use Frickelbruder\KickOff\Log\Listener\CsvLogListener;
use Frickelbruder\KickOff\Tests\Log\RuleStub;

class CsvLogListenerTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var CsvLogListener
     */
    private $listener = null;

    protected function setup() {
        $this->listener = new CsvLogListener();
        $this->listener->logFileName = __DIR__ . '/files/' . uniqid('log_') . '.csv';
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

        $this->listener->log('Section1', 'http://www.test1.com', $rule, true);
        $this->listener->log('Section1', 'http://www.test1.com', $rule2, false);
        $this->listener->log('Section2', 'http://www.test2.com', $rule3, true);

        $this->listener->finish();

        $this->assertFileExists($this->listener->logFileName);

        $generatedFile = file_get_contents($this->listener->logFileName);
        $exampleFile = file_get_contents( __DIR__ . '/files/main.csv');

        $this->assertEquals($this->normalizeEOL($generatedFile), $this->normalizeEOL($exampleFile));
    }

    private function normalizeEOL($string) {
        return str_replace("\r\n", "\n", $string);
    }

}
