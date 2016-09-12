<?php
namespace Frickelbruder\KickOff\Tests\Cli;

use Frickelbruder\KickOff\Cli\Commands\DefaultCommand;

class DefaultCommandProxy extends DefaultCommand {

    public function executeProxy($path, $sectionName) {

        $this->buildConfiguration($path);

        $sections = $this->configuration->getSections();

        $this->handleSection($sections[$sectionName]);

        return $this->errorCount;
    }

}