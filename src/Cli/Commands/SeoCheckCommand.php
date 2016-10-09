<?php
namespace Frickelbruder\KickOff\Cli\Commands;

use Frickelbruder\KickOff\App\Application;
use Frickelbruder\KickOff\App\KickOff;
use Frickelbruder\KickOff\Log\Listener\JunitLogListener;
use Frickelbruder\KickOff\Log\Listener\ListenerFactory;
use Frickelbruder\KickOff\Log\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SeoCheckCommand extends Command {

    /**
     * @var KickOff
     */
    private $mainApplication;

    protected function configure()
    {
        $this
            ->setName('seocheck')
            ->setDescription('quickchecks a webpage for basic SEO rules')
            ->addArgument(
                'webpage',
                InputArgument::REQUIRED,
                'the webpage to check'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Kickoff SEO Test");
        $output->writeln("");

        $configFile = __DIR__ . '/../../config/Seo.yml';
        $webpage = $input->getArgument('webpage');

        return $this->mainApplication->index($configFile, $webpage);

    }

    public function setMainApplication(KickOff $mainApplication) {
        $this->mainApplication = $mainApplication;
    }

}