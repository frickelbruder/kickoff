<?php
namespace Frickelbruder\KickOff\Configuration;

use Frickelbruder\KickOff\Rules\ConfigurableRuleInterface;
use Frickelbruder\KickOff\Rules\Exceptions\RuleNotConfigurableException;
use Frickelbruder\KickOff\Rules\RuleInterface;

class RuleBuilder {

    public function buildRules($config) {
        $rules = array();
        foreach($config as $name => $values) {
            $className = $values['class'];
            $class = new $className();
            $this->addMethodCalls( $values, $class );
            $this->addConfiguration( $values, $class );
            $rules[$name] = $class;
        }
        return $rules;
    }

    /**
     * @param array $config
     * @param RuleInterface $class
     */
    private function addMethodCalls($config, RuleInterface $class) {
        if( isset( $config['calls'] ) && is_array( $config['calls'] ) ) {
            foreach( $config['calls'] as $calls ) {
                $method = $calls[0];
                $params = $calls[1];
                call_user_func_array( array( $class, $method ), $params );
            }
        }

    }

    private function addConfiguration($config, $class) {
        if(empty($config['configuration'])) {
            return;
        }
        if(!(isset($config['configuration']) && $class instanceof ConfigurableRuleInterface)) {
            throw new RuleNotConfigurableException($config['class'] . ' is not configurable.');
        }
        $this->addMethodCalls(array('calls' => $config['configuration']), $class);
    }

}