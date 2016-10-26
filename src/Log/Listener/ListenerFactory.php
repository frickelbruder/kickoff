<?php
namespace Frickelbruder\KickOff\Log\Listener;

use Symfony\Component\Console\Output\ConsoleOutput;

class ListenerFactory {

    /**
     * @param string
     *
     * @return Listener
     */
    public function get($name) {

        switch($name) {
            case 'junit-file':
                return new JunitLogListener();
            case 'console':
                return new ConsoleOutputListener(new ConsoleOutput);
            case 'csv-file':
                return new CsvLogListener();
        }
    }

}