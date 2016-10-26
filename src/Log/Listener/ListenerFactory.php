<?php
namespace Frickelbruder\KickOff\Log\Listener;

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
                return new ConsoleOutputListener();
            case 'csv-file':
                return new CsvLogListener();
        }

    }

}