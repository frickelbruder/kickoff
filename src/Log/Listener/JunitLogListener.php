<?php
namespace Frickelbruder\KickOff\Log\Listener;

use Frickelbruder\KickOff\Rules\RuleInterface;

class JunitLogListener implements Listener {

    public $logFileName = 'kickoff.xml';

    private $logs = array();

    public function log($sectionName, $targetUrl, RuleInterface $rule, $success) {
        $ruleName = $rule->getName();
        if(!isset($this->logs[$sectionName])) {
            $this->logs[$sectionName] = array();
        }
        if(!isset($this->logs[$sectionName][$ruleName])) {
            $this->logs[$sectionName][$ruleName] = array();
        }
        $this->logs[$sectionName][$ruleName] = $success;
    }

    public function finish() {

        $document = new \SimpleXMLElement('<testsuites/>');

        foreach($this->logs as $section => $sectionData) {
            $testsuite = $document->addChild( 'testsuite' );
            $testsuite->addAttribute('name', $section);
            $testsuite->addAttribute('tests', count($sectionData));
            $testsuite->addAttribute('assertions', count($sectionData));
            $this->addTestCasesToSuite($sectionData, $testsuite);
        }

        $document->saveXML($this->logFileName);


    }

    /**
     * @param array $sectionData
     * @param \SimpleXMLElement $testsuite
     */
    private function addTestCasesToSuite(array $sectionData, \SimpleXMLElement$testsuite) {
        $failures = 0;
        foreach ($sectionData as $ruleName => $successInfo) {
            $testcase = $testsuite->addChild('testcase');
            $testcase->addAttribute('name', $ruleName);
            $testcase->addAttribute('assertions', 1);
            if (!$successInfo) {
                $failure = $testcase->addChild('failure');
                $failure->addAttribute('type', 'Kickoff\Rule\Fail\Exception');
                $failures++;
            }
        }
        $testsuite->addAttribute('failures', $failures);
    }

}