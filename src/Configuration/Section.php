<?php
namespace Frickelbruder\KickOff\Configuration;

use Frickelbruder\KickOff\Rules\RuleInterface;

class Section {

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var RuleInterface[]
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
     * @param RuleInterface[] $rules
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
     * @return RuleInterface[]
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