<?php
namespace Frickelbruder\KickOff\Configuration;

use Frickelbruder\KickOff\Rules\RequiresHeaderInterface;
use Frickelbruder\KickOff\Rules\RuleInterface;
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
        $config = $this->buildConfig( $filename );
        $this->prepareConfiguredItems( $config );
        $this->buildSections( $config );
    }

    private function buildConfig($filename) {
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
        $defaultRules = $defaultRulesConfig['Rules'];
        $configRules = !empty( $config['Rules'] ) ? $config['Rules'] : array();

        $config['Rules'] = array_merge( $defaultRules, $configRules );

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
        if(isset($config['headers'])) {
            foreach($config['headers'] as $header) {
                $targetUrl->addHeader($header[0], $header[1]);
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
        $result = array();
        foreach( $sectionConfig['rules'] as $name ) {
            $plainName = $name;
            if( is_array( $name ) ) {
                list( $plainName, $configData ) = each( $name );
                foreach( $configData as $variableBlock ) {
                    $rule['configuration'][] = array( 'set', $variableBlock );
                }
            }
            $rule = $mainConfig['Rules'][ $plainName ];
            $result[ $plainName ] = $rule;
        }

        return $this->generateRules( $result );
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
            foreach( $headers as $header ) {
                if( empty( $config['config'] ) ) {
                    $config['config'] = array();
                }
                if( empty( $config['config']['headers'] ) ) {
                    $config['config']['headers'] = array();
                }
                $config['config']['headers'][] = $header;
            }
        }

        return $config;
    }


}