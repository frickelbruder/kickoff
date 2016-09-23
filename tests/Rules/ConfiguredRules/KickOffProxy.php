<?php
namespace Frickelbruder\KickOff\Tests\Rules\ConfiguredRules;

use Frickelbruder\KickOff\App\KickOff;

class KickOffProxy extends KickOff {

    protected $activeSection = '';

    public function indexProxy($path, $section) {
        $this->activeSection = $section;
        return parent::index($path);
    }

    protected function getSections() {
        $sections = parent::getSections();
        if($this->activeSection !== '' && isset($sections[$this->activeSection])) {
            return array($sections[$this->activeSection]);
        }
        return $sections;
    }

}