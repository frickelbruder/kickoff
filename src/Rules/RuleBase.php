<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;

abstract class RuleBase implements RuleInterface {

    public $name = '';

    /**
     * @var HttpResponse
     */
    protected $httpResponse = null;

    protected $errorMessage = 'This Rule did not yield the expected result.';

    public function __construct() {
        if(empty($this->name)) {
            $this->name = get_called_class();
        }
    }

    public function setHttpResponse(HttpResponse $httpResponse) {
        $this->httpResponse = $httpResponse;
    }

    public function getName() {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    public function getErrorMessage() {
        return $this->errorMessage;
    }

    /**
     * @param string $body
     *
     * @return \SimpleXMLElement
     */
    protected function getResponseBodyAsXml($body) {
        libxml_use_internal_errors( true );
        $doc = new \DOMDocument();
        $doc->strictErrorChecking = false;
        $doc->loadHTML( '<?xml encoding="utf-8" ?>' . $body );
        $xml = simplexml_import_dom( $doc );

        return $xml;
    }

    protected function getDomElementFromBodyByXpath($xpath) {
        $body = $this->httpResponse->getBody();
        if(empty($body)) {
            return '';
        }
        $xml = $this->getResponseBodyAsXml( $body );
        return $xml->xpath($xpath);
    }

    protected function findHeader($headerName) {
        $loweredHeaderName = strtolower($headerName);
        $headers = $this->httpResponse->getHeaders();
        foreach($headers as $key => $header) {
            if(strtolower($key) == $loweredHeaderName) {
                return $header;
            }
        }
        throw new HeaderNotFoundException('The HTTP header "' . $headerName. '" is missing.');
    }

    protected function findNormalizedHeader($headerName) {
        $header = $this->findHeader($headerName);
        return $this->getNormalizedHeaderItem($header);
    }

    private function getNormalizedHeaderItem($header) {
        if(is_array($header) ) {
            return implode("\n", $header);
        }
        return $header;
    }


}