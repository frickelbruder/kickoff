<?php
namespace Frickelbruder\KickOff\Rules;

abstract class RuleBase implements Rule {

    public $name = '';

    public function __construct() {
        if(empty($this->name)) {
            $this->name = get_called_class();
        }
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