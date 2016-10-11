<?php
namespace Frickelbruder\KickOff\Rules;

class OgPropertyPresent extends ConfigurableRuleBase {

    public $name = 'OgPropertyPresent';

    protected $errorMessage = 'The required open graph property is not present.';

    protected $requiredProperties = array( 'title', 'description', 'url', 'image' );

    protected $configurableField = array( 'requiredProperties' );

    public function validate() {
        $crawler = $this->getCrawler();

        foreach( $this->requiredProperties as $propertyName ) {
            $propertyElement = $crawler->filterXPath( './html/head/meta[@property="og:' . $propertyName . '"]' );
            if(!count($propertyElement)) {
                $this->errorMessage = 'The open graph property "' . $propertyName . '" was not found on the site.';

                return false;
            }
            $length = mb_strlen( $propertyElement->eq(0)->attr('content'), 'UTF-8' );

            if( $length == 0 ) {
                $this->errorMessage = 'The open graph property "' . $propertyName . '" was found but is empty.';

                return false;
            }
        }

        return true;
    }
}