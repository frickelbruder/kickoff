<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;

class LinkRelCanonicalRule extends RuleBase {

    public $name = 'LinkRelCanonicalRule';

    protected $errorMessage = 'The page is missing a <link rel="canonical"> tag or header.';

    public function validate() {
        $canonicals = array('header' => '');
        try {
            $linkHeader = $this->findHeader( 'Link' );
            foreach($linkHeader as $header) {
                if( strpos( $header, 'rel=canonical' ) !== false || strpos( $header, 'rel="canonical"' ) !== false ) {
                    $canonicals['header'][] = $header;
                }
            }
        } catch(HeaderNotFoundException $e) {}

        $canonicals['body'] = $this->getDomElementFromBodyByXpath('/html/head/link[@rel="canonical"]/ @href');

        return $this->validateCanonicals($canonicals);

    }

    /**
     * @param array $canonicals
     * @return bool
     */
    private function validateCanonicals($canonicals) {

        if(empty($canonicals['header']) && empty($canonicals['body'])) {
            return false;
        }

        if(!empty($canonicals['header']) && !empty($canonicals['body'])) {
            $this->errorMessage = 'There are canonical HTTP-header tags as well as <link>-Tags in the page body.';
            return false;
        }

        if(!empty($canonicals['body'])) {
            return $this->validateBody($canonicals['body']);
        }
        if(!empty($canonicals['header'])) {
            return $this->validateHeader($canonicals['header']);
        }

        return true;
    }

    private function validateBody($canonical) {
        if( strlen( $canonical[0]['href'] ) == 0 ) {
            $this->errorMessage = 'The  rel-canonical tag is set, but points to an empty path';

            return false;
        }
        if( count( $canonical ) > 1 ) {
            $this->errorMessage = 'There are multiple <link rel="canonical"> tags on this page.';
            return false;
        }
        return true;
    }

    /**
     * @param array $canonical
     * @return bool
     */
    private function validateHeader($canonical) {
        if( count( $canonical ) > 1 ) {
            $this->errorMessage = 'There are multiple "Link: ... rel=canonical" headers on this page.';
            return false;
        }
        preg_match( '~<(.*)>~', $canonical[0], $canonicalUrl);
        if( strlen($canonicalUrl[1]) == 0 ) {
            $this->errorMessage = 'The rel-canonical header is set, but points to an empty path.';
            return false;
        }

        if( strpos($canonical[0], '<') === false ) {
            $this->errorMessage = 'The rel-canonical header seems to be malformed.';
            return false;
        }


        return true;
    }


}