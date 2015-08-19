<?php
/**
 * Created by PhpStorm.
 * User: Fred
 * Date: 17.08.2015
 * Time: 00:24
 */

namespace frickelbruder\KickOff\Configuration;


use Symfony\Component\Yaml\Yaml;

class Configuration {

    /**
     * @var Section[]
     */
    private $sections = array();

    private $port = '80';
    private $host = '';
    private $scheme = 'http';

    private $map = array('host' => 'host', 'port' => 'port', 'scheme' => 'scheme');

    public function buildFromFile($filename) {
        $configArray = Yaml::parse(file_get_contents($filename));

        $this->build($configArray);
    }

    public function getSections() {
        return $this->sections;
    }

    private function build(Array $config) {
        if(isset($config['options'])) {
            $config = $this->mapBasicValues($config['options']);
        }
    }

    private function mapBasicValues(Array $config) {
        $resultConfig = $config;
        foreach($this->map as $configKey=>$classProperty) {
            if(isset($config[$configKey])) {
                $this->$classProperty = $config[$configKey];
                unset($resultConfig[$configKey]);
            }
        }
        return $resultConfig;
    }

}