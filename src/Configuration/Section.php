<?php
namespace Frickelbruder\KickOff\Configuration;

use Frickelbruder\KickOff\Rules\Rule;

class Section {

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var Rule[]
     */
    protected $rules = array();

    /**
     * @var TargetUrl
     */
    protected $targetUrl = null;

    public function __construct($name) {
        $this->name = $name;
    }

    /**
     * @param Rule[] $rules
     */
    public function setRules($rules) {
        $this->rules = $rules;
    }

    public function setTargetUrlItem(TargetUrl $targetUrl) {
        $this->targetUrl = $targetUrl;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @return Rule[]
     */
    public function getRules() {
        return $this->rules;
    }

    /**
     * @return TargetUrl
     */
    public function getTargetUrl() {
        return $this->targetUrl;
    }


}