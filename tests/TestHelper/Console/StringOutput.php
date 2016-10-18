<?php

namespace Frickelbruder\KickOff\Tests\TestHelper\Console;

/**
 * Class StringOutput
 *
 * Helper Class to Output Console Content as String
 *
 * @package Frickelbruder\KickOff\Tests\TestHelper\Console
 */
class StringOutput {

    /** @var string */
    private $output = '';

    /**
     * @param $content
     */
    public function write($content) {
        $this->output .= $content;
    }

    /**
     * @param $content
     */
    public function writeln($content) {
        $this->output .= $content . PHP_EOL;
    }

    /**
     * @return string
     */
    public function getOutput() {
        return $this->output;
    }
}
