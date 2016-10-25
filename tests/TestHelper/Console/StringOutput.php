<?php

namespace Frickelbruder\KickOff\Tests\TestHelper\Console;

use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * Class StringOutput
 *
 * Helper Class to Output Console Content as String
 *
 * @package Frickelbruder\KickOff\Tests\TestHelper\Console
 */
class StringOutput extends ConsoleOutput {

    /** @var string */
    private $output = '';

    public function writeln($messages, $options = self::OUTPUT_NORMAL)
    {
        $this->output .= $messages . PHP_EOL;
    }

    public function write($messages, $newline = false, $options = self::OUTPUT_NORMAL)
    {
        $this->output .= $messages;
    }

    /**
     * @return string
     */
    public function getOutput() {
        return $this->output;
    }
}
