<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;

abstract class RuleBase implements Rule {

    public $name = '';

    /**
     * @var HttpResponse
     */
    protected $httpResponse = null;

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
        return 'The Rule "' . $this->getName(). '" in section "%SECTION%" did not yield the expected result.';
    }


}