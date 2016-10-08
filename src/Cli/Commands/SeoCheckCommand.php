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
     * @var Logger
     */
    protected $logger = null;

    /**
     * @var KickOff
     */
    private $mainApplication;


    private $logs = array(
        array('name' => 'junit-file', 'shortcut' => 'j', 'mode' => InputOption::VALUE_OPTIONAL, 'description' => 'path to a junit compatible log file'),
        array('name' => 'csv-file', 'shortcut' => 'c', 'mode' => InputOption::VALUE_OPTIONAL, 'description' => 'path to a csv log file'),
    );

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
        foreach($this->logs as $log) {
            $this->addOption(
                $log['name'],
                $log['shortcut'],
                $log['mode'],
                $log['description']
            );
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Kickoff SEO Test");
        $output->writeln("");

        $this->handleLogOptions($input);

        return $this->mainApplication
                    ->seocheck($input->getArgument('webpage'));

    }

    private function handleLogOptions(InputInterface $input) {

        $listenerFactory = new ListenerFactory();

        foreach($this->logs as $log) {

            $logPath = $input->getOption($log['name']);
            if(empty($logPath)) {
                continue;
            }

            $logListener = $listenerFactory->get($log['name']);
            $logListener->logFileName = $logPath;
            $this->logger->addListener($log['name'], $logListener);

        }

    }

    public function setMainApplication(KickOff $mainApplication) {
        $this->mainApplication = $mainApplication;
    }

    /**
     * @param Logger $logger
     */
    public function setLogger(Logger $logger) {
        $this->logger = $logger;
    }

}