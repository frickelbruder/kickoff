<?php
namespace Frickelbruder\KickOff\Tests\Cli;

use Frickelbruder\KickOff\Cli\Commands\DefaultCommand;

class DefaultCommandProxy extends DefaultCommand {

    public function executeProxy($path) {

        $this->buildConfiguration($path);

        foreach($this->configuration->getSections() as $sectionName => $section) {
            $this->handleSection($section);
        }

        return $this->errorCount;
    }

}