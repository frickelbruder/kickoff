<?php
namespace Frickelbruder\KickOff\Configuration;

use Frickelbruder\KickOff\Configuration\Exceptions\UnknownRuleException;
use Frickelbruder\KickOff\Configuration\Exceptions\UnnownRulesetException;
use Frickelbruder\KickOff\Rules\Interfaces\RequiresHeaderInterface;
use Frickelbruder\KickOff\Rules\Interfaces\RuleInterface;
use Frickelbruder\KickOff\Yaml\Yaml;

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
     * @var Yaml
     */
    private $yaml = null;

    public function __construct(Yaml $yaml) {
        $this->yaml = $yaml;
        $this->defaultTargetUrl = new TargetUrl();
    }

    public function getSections() {
        return $this->sections;
    }

    public function build($filename) {
        $config = $this->readConfigs( $filename );
        $this->prepareConfiguredItems( $config );
        $this->buildSections( $config );
    }

    private function readConfigs($filename) {
        $config = $this->yaml->fromFile( $filename );
        $defaultRulesConfig = $this->yaml->fromFile( __DIR__ . '/../config/Rules.yml' );
        $config = $this->mergeUserConfigWithDefaults( $config, $defaultRulesConfig );

        return $config;
    }

    /**
     * @param array $config
     * @param array $defaultRulesConfig
     *
     * @return mixed
     */
    private function mergeUserConfigWithDefaults(array $config, array $defaultRulesConfig) {
        $defaultRules = $defaultRulesConfig['Rule definitions'];
        $configRules = !empty( $config['Rule definitions'] ) ? $config['Rule definitions'] : array();

        $config['Rule definitions'] = array_merge( $defaultRules, $configRules );

        return $config;
    }

    /**
     * @param $config
     */
    protected function prepareConfiguredItems($config) {
        if( isset( $config['defaults']['target'] ) ) {
            $this->buildDefaultTarget( $config['defaults']['target'] );
        }
    }

    private function buildDefaultTarget($config) {
        $this->enrichTarget( $this->defaultTargetUrl, $config );
    }

    private function enrichTarget(TargetUrl $targetUrl, $config) {
        foreach( TargetUrl::$components as $key ) {
            if( array_key_exists( $key, $config ) ) {
                $targetUrl->$key = $config[ $key ];
            }
        }
        if( isset( $config['headers'] ) ) {
            foreach( $config['headers'] as $name => $value ) {
                $targetUrl->addHeader( $name, $value );
            }
        }
        if( isset( $config['auth'] ) && !empty($config['auth']['username']) && !empty($config['auth']['password'])) {
            $targetUrl->setCredentials( $config['auth']['username'], $config['auth']['password'] );
        }
    }

    /**
     * @param $config
     *
     * @return RuleInterface[]
     */
    private function generateRules($config) {
        $ruleBuilder = new RuleBuilder();
        return $ruleBuilder->buildRules( $config );
    }

    /**
     * @param $config
     */
    protected function buildSections($config) {
        foreach( $config['Sections'] as $name => $sectionConfig ) {
            $sectionRules = $this->getRulesForSection( $sectionConfig, $config );
            $section = new Section( $name );
            $sectionTargetUrl = $this->getSectionTargetUrl( $sectionConfig, $sectionRules );
            $section->setTargetUrlItem( $sectionTargetUrl );
            $section->setRules( $sectionRules );
            $this->sections[ $name ] = $section;
        }
    }

    private function getRulesForSection($sectionConfig, $mainConfig) {
        $rules = $this->getSectionRules($sectionConfig, $mainConfig);
        $rulesetRules = $this->getSectionRulesetRules($sectionConfig, $mainConfig);

        $rules = array_merge($rules, $rulesetRules);

        if( empty( $rules ) ) {
            return array();
        }
        $result = $this->buildRulesConfiguration( $rules, $mainConfig );
        return $this->generateRules( $result );
    }

    private function getSectionRules($sectionConfig, $mainConfig) {
        $rules = array();
        if(isset($mainConfig['defaults']['rules'])) {
            $rules = $mainConfig['defaults']['rules'];
        }

        if(isset($sectionConfig['rules'])) {
            $rules = array_merge($rules, $sectionConfig['rules']);
        }
        return $rules;
    }

    private function getSectionRulesetRules($sectionConfig, $mainConfig) {
        $rules = array();
        if(isset($mainConfig['defaults']['rulesets'])) {
            $rules = array_merge($rules, $this->getRulesForRulesets($mainConfig['defaults']['rulesets'], $mainConfig));
        }

        if(!isset($sectionConfig['rulesets'])) {
            return $rules;
        }

        $rules = array_merge($rules, $this->getRulesForRulesets($sectionConfig['rulesets'], $mainConfig));
        return $rules;
    }

    private function getRulesForRulesets($rulesets, $mainConfig) {
        $rules = array();
        foreach($rulesets as $ruleset) {
            $rulesetRules = $this->getRulesForRuleset($ruleset, $mainConfig);
            $rules = array_merge($rules, $rulesetRules);
        }
        return $rules;
    }

    private function getRulesForRuleset($name, $mainConfig) {
        $rulsets = $this->getRulesets($mainConfig);

        if(!isset($rulsets[$name])) {
            throw new UnnownRulesetException('Ruleset "' . $name . '" is not defined.');
        }

        return $rulsets[$name];
    }

    private function getRulesets($mainConfig) {
        $rulesets = array();
        if (isset($mainConfig['Rulesets'])) {
            $rulesets = $mainConfig['Rulesets'];
        }
        return $rulesets;
    }

    /**
     * @param $sectionRules
     * @param $mainConfig
     *
     * @return array
     */
    private function buildRulesConfiguration($sectionRules, $mainConfig) {
        $result = array();
        foreach( $sectionRules as $name ) {
            $plainName = $name;
            $configuration = array();
            if( is_array( $name ) ) {
                list( $plainName, $configData ) = each( $name );
                 $configuration = $this->addConfigurationToRuleConfig( $configData );
            }
            $rule = $this->fetchRuleBase( $plainName, $mainConfig );
            $rule['configuration'] = $configuration;
            $result[ $plainName ] = $rule;
        }

        return $result;
    }

    private function fetchRuleBase($name, $config) {
        if( !isset( $config['Rule definitions'][ $name ] ) ) {
            throw new UnknownRuleException( 'Rule definition "' . $name . '" not known.' );
        }

        return $config['Rule definitions'][ $name ];
    }

    /**
     * @param $configData
     *
     * @return array
     */
    private function addConfigurationToRuleConfig($configData) {
        $configuration = array();
        foreach( $configData as $variableName => $variableValue ) {
            $configuration[] = array( 'set', array($variableName, $variableValue) );
        }

        return $configuration;
    }

    private function getSectionTargetUrl($config, $rules) {
        $target = clone $this->defaultTargetUrl;

        $config = $this->addRuleRequirementsToConfig( $config, $rules );

        if( !empty( $config['target'] ) ) {
            $this->enrichTarget( $target, $config['target'] );
        }

        return $target;
    }

    /**
     * @param array $config
     * @param array $rules
     *
     * @return array
     */
    private function addRuleRequirementsToConfig($config, array $rules) {
        foreach( $rules as $rule ) {
            if( !( $rule instanceof RequiresHeaderInterface ) ) {
                continue;
            }
            $headers = $rule->getRequiredHeaders();
            $config = $this->addRequiredHeadersToTargetConfig( $config, $headers );
        }

        return $config;
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function ensureTargetHeader($config) {
        if( empty( $config['target'] ) ) {
            $config['target'] = array();
        }
        if( empty( $config['target']['headers'] ) ) {
            $config['target']['headers'] = array();
        }

        return $config;
    }

    /**
     * @param array $config
     * @param array $headers
     *
     * @return array
     */
    private function addRequiredHeadersToTargetConfig($config, $headers) {
        if(count($headers) == 0) {
            return array();
        }
        $config = $this->ensureTargetHeader( $config );
        $config['target']['headers'] = array_merge($config['target']['headers'], $headers);

        return $config;
    }

}