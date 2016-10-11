<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;
use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;
use Symfony\Component\DomCrawler\Crawler;

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

    public function getReadableName() {
        $regex = '/(?#! splitCamelCase Rev:20140412)
            # Split camelCase "words". Two global alternatives. Either g1of2:
              (?<=[a-z])      # Position is after a lowercase,
              (?=[A-Z])       # and before an uppercase letter.
            | (?<=[A-Z])      # Or g2of2; Position is after uppercase,
              (?=[A-Z][a-z])  # and before upper-then-lower case.
            /x';
        return implode(' ',preg_split($regex, ucfirst($this->name)));
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

    protected function getCrawler()
    {
        $body = $this->httpResponse->getBody();
        $contentType = null;

        foreach ($this->httpResponse->getHeaders() as $key => $value) {
            if (strtolower($key) == 'content-type') {
                $contentType = $value[0];
                break;
            }
        }

        $crawler = new Crawler();
        $crawler->addContent($body, $contentType);

        return $crawler;
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
