<?php

namespace Frickelbruder\KickOff\Cli\Commands;

use Frickelbruder\KickOff\Cli\Commands\Contracts\KickoffCommand;

class ResourceCommand extends KickoffCommand
{
  protected function configure()
  {
    parent::configure();
    $this
      ->setName('resource')
      ->setDisplayTitle('Resource Test')
      ->setDescription('Kickoff a test against a web resource')
      ->addArgument(
        'resourceUrl',
        InputArgument::REQUIRED,
        'the web resource to review (html, image, css, xml, etc)'
      );
  }

  protected function execute(InputInterface $input, OutputInterface $output)
  {
    parent::execute($input, $output);
    $configFile = __DIR__ . '/../../config/Resource.yml';
    return $this->mainApplication->index($configFile, $input->getArgument('resourceUrl'));
  }
}
