<?php
namespace Frickelbruder\KickOff\Configuration;

use Frickelbruder\KickOff\Configuration\Exceptions\UnknownRuleException;
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
        foreach( array( 'host', 'port', 'uri', 'scheme' ) as $key ) {
            if( array_key_exists( $key, $config ) ) {
                $targetUrl->$key = $config[ $key ];
            }
        }
        if( isset( $config['headers'] ) ) {
            foreach( $config['headers'] as $header ) {
                $targetUrl->addHeader( $header[0], $header[1] );
            }
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
        if( empty( $sectionConfig['rules'] ) ) {
            return array();
        }
        $result = $this->buildRulesConfiguration( $sectionConfig, $mainConfig );
        return $this->generateRules( $result );
    }

    /**
     * @param $sectionConfig
     * @param $mainConfig
     *
     * @return array
     */
    private function buildRulesConfiguration($sectionConfig, $mainConfig) {
        $result = array();
        foreach( $sectionConfig['rules'] as $name ) {
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
            throw new UnknownRuleException( 'Rule definition "' . $name . "' not known.'" );
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
        foreach( $configData as $variableBlock ) {
            $configuration[] = array( 'set', $variableBlock );
        }

        return $configuration;
    }

    private function getSectionTargetUrl($config, $rules) {
        $target = clone $this->defaultTargetUrl;

        $config = $this->addRuleRequirementsToConfig( $config, $rules );

        if( !empty( $config['config'] ) ) {
            $this->enrichTarget( $target, $config['config'] );
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
            $config = $this->addRequiredHeadersToConfig( $config, $headers );
        }

        return $config;
    }

    /**
     * @param array $config
     *
     * @return array
     */
    private function ensureConfigHeader($config) {
        if( empty( $config['config'] ) ) {
            $config['config'] = array();
        }
        if( empty( $config['config']['headers'] ) ) {
            $config['config']['headers'] = array();
        }

        return $config;
    }

    /**
     * @param array $config
     * @param array $headers
     *
     * @return array
     */
    private function addRequiredHeadersToConfig($config, $headers) {
        foreach( $headers as $header ) {
            $config = $this->ensureConfigHeader( $config );
            $config['config']['headers'][] = $header;
        }

        return $config;
    }




}