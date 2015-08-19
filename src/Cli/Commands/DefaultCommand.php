<?php
namespace frickelbruder\KickOff\Cli\Commands;

use Ivory\HttpAdapter\CurlHttpAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DefaultCommand extends Command {

    protected $configuration = null;

    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Runs your kickoff')
            ->addArgument(
                'configfile',
                InputArgument::REQUIRED,
                'the config file to use'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->buildConfiguration($input->getArgument('configfile'));

        foreach($this->configuration->getSection() as $sectionName => $section) {

            $result = $this->handleSection($sectionName, $section);
            $output->writeln($result);

        }

    }

    protected function buildConfiguration($configFile) {
        $this->configuration->buildFromFile($configFile);
    }

    protected function handleSection($sectionname, $section) {

    }

}