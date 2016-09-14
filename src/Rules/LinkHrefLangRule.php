<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;

class LinkHrefLangRule extends RuleBase {

    public $name = 'LinkHrefLangRule';

    protected $errorMessage = 'The page is missing a <link rel="alternate" hreflang="..."> tag or header.';

    public function validate() {

        $hrefLangItems = array('header' => '');
        try {
            $linkHeader = $this->findHeader( 'Link' );
            if( preg_match( '~rel=["\']?alternate["\']?~', $linkHeader ) > 0 && strpos( $linkHeader, 'hreflang=' ) !== false
            ) {
                $headerItems = explode(',', $linkHeader);
                $normalizedItems = $this->normalizeHeaderItems($headerItems);
                if($normalizedItems === false) {
                    return false;
                }
                $hrefLangItems['header'] = $normalizedItems;
            }

        } catch(HeaderNotFoundException $e) {}

        $bodyItems = $this->getDomElementFromBodyByXpath('/html/head/link[@rel="alternate"][@hreflang]');
        $hrefLangItems['body'] = $this->normalizeBodyItems($bodyItems);

        return $this->validateHrefLang($hrefLangItems);

    }

    /**
     * @param $hrefLangItems
     *
     * @return bool
     */
    private function validateHrefLang($hrefLangItems) {

        if(empty($hrefLangItems['header']) && empty($hrefLangItems['body'])) {
            return false;
        }

        if(!empty($hrefLangItems['header']) && !empty($hrefLangItems['body'])) {
            $this->errorMessage = 'There are hrefLang HTTP-header tags as well as <link rel="alternate" hreflang="...">-Tags in the page body.';
            return false;
        }

        $itemsToTest = $hrefLangItems['body'];
        if(!empty($hrefLangItems['header'])) {
            $itemsToTest = $hrefLangItems['header'];
        }

        return $this->validateHrefLangItems($itemsToTest);
    }

    private function normalizeHeaderItems($hrefLangItems) {
        $normalizedItems = array();
        foreach($hrefLangItems as $hrefLangItem) {
            if(!preg_match( '~<(.*)>~', $hrefLangItem, $hrefLangResult)) {
                $this->errorMessage = 'The hreflang header is set, but seems to be broken:' . $hrefLangItem;
                return false;
            }
            $hrefLangHref = $hrefLangResult[1];
            if(!preg_match( '~hreflang=[\'"]?([a-z]{2}|x-default)[\'"]?~', $hrefLangItem, $hrefLangValueResult)) {
                $this->errorMessage = 'The hreflang header is set, but seems to be broken:' . $hrefLangItem;
                return false;
            }
            $hrefLangValue = $hrefLangValueResult[1];
            $normalizedItems[] = array(
                'hreflang' => (string) $hrefLangValue,
                'href' => (string) $hrefLangHref
            );
        }
        return $normalizedItems;
    }

    private function normalizeBodyItems($hrefLangItems) {
        if(!is_array($hrefLangItems)) {
            return array();
        }
        $normalizedItems = array();
        foreach($hrefLangItems as $hrefLangItem) {
            $normalizedItems[] = array(
                'hreflang' => (string) $hrefLangItem['hreflang'],
                'href' => (string) $hrefLangItem['href']
            );
        }
        return $normalizedItems;
    }

    private function validateHrefLangItems($hrefLangItems) {


        $currentUri = $this->httpResponse->getRequest()->getUrl();
        $currentUriReferenced = false;
        $foundHrefLangs = array();
        foreach($hrefLangItems as $hrefLangItem) {
            $hrefLangValue = $hrefLangItem['hreflang'];
            $hrefLangHref = $hrefLangItem['href'];
            if(!$this->validateHrefLangValue( $hrefLangValue )) {
                return false;
            }
            if(!$this->validateUniqueHrefLang( $foundHrefLangs, $hrefLangValue )) {
                return false;
            }

            $currentUriReferenced = $currentUriReferenced || ($hrefLangHref == $currentUri);

        }

        if($currentUriReferenced == false) {
            $this->errorMessage = 'The current URL needs to be referenced.';
            return false;
        }
        return true;
    }



    /**
     * @param $hrefLangValue
     *
     * @return mixed
     */
    private function validateHrefLangValue($hrefLangValue) {
        if( !preg_match( '~^([a-z]{2})(-[A-Za-z][a-z]+)?~', $hrefLangValue ) && 'x-default' != $hrefLangValue ) {
            $this->errorMessage = 'One "hreflang" attribute has the unexpected value "' . $hrefLangValue . '"';
            return false;
        }

        return true;
    }

    /**
     * @param $foundHrefLangs
     * @param $hrefLangValue
     *
     * @return bool
     */
    private function validateUniqueHrefLang(&$foundHrefLangs, $hrefLangValue) {
        if( isset( $foundHrefLangs[ $hrefLangValue ] ) ) {
            $this->errorMessage = 'The hreflang value "' . $hrefLangValue . '" is referenced multiple times';
            return false;
        }
        $foundHrefLangs[ $hrefLangValue ] = $hrefLangValue;
        return true;

    }


}