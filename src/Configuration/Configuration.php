<?php
namespace Frickelbruder\KickOff\Configuration;


use Symfony\Component\Yaml\Yaml;

class Configuration {

    /**
     * @var Section[]
     */
    private $sections = array();

    /**
     * @var TargetUrl
     */
    private $defaultTargetUrl = null;

    /**
     * @var array
     */
    private $rules = array();

    public function buildFromFile($filename) {
        $configArray = Yaml::parse(file_get_contents($filename));

        $this->build($configArray);
    }

    public function getSections() {
        return $this->sections;
    }

    private function build(Array $config) {
        $this->defaultTargetUrl = new TargetUrl();

        if( isset( $config['defaults']['target'] )) {
            $this->buildDefaultTarget($config['defaults']['target']);
        }
        if(isset($config['Rules'])) {
            $this->generateRules($config['Rules']);
        }
        foreach($config['Sections'] as $name => $sectionConfig) {
            $section = new Section($name);
            $sectionTargetUrl = $this->getSectionTargetUrl($sectionConfig);
            $section->setTargetUrlItem($sectionTargetUrl);
            $section->setRules($this->getRulesForSection($sectionConfig));
            $this->sections[$name] = $section;
        }
    }

    private function getRulesForSection($config) {
        if(empty($config['rules'])) {
            return array();
        }
        $result = array();
        foreach($config['rules'] as $name) {
            $result[$name] = $this->rules[$name];
        }
        return $result;
    }

    private function getSectionTargetUrl($config){
        $target = clone $this->defaultTargetUrl;

        if(empty($config['config'])) {
            return $target;
        }
        foreach(array('host', 'port', 'uri', 'scheme') as $key) {
            if( array_key_exists( $key, $config['config'] ) ) {
                $target->$key = $config['config'][ $key ];
            }
        }
        return $target;
    }

    private function buildDefaultTarget($config) {
        foreach(array('host', 'port', 'uri', 'scheme') as $key) {
            if( array_key_exists( $key, $config ) ) {
                $this->defaultTargetUrl->$key = $config[ $key ];
            }
        }
    }

    private function generateRules($config) {

        foreach($config as $name => $values) {
            $className = $values['class'];
            $class = new $className();
            if(isset($values['calls']) && is_array($values['calls'])) {
                foreach($values['calls'] as $calls) {
                    $method = $calls[0];
                    $params = $calls[1];
                    call_user_func_array(array($class, $method), $params);
                }
            }
            $this->rules[$name] = $class;
        }

    }

}