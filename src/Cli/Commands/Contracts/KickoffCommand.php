<?php

namespace Frickelbruder\KickOff\Cli\Commands;

use Frickelbruder\KickOff\App\KickOff;
use Frickelbruder\KickOff\Log\Listener\ListenerFactory;
use Frickelbruder\KickOff\Log\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class KickoffCommand extends Command
{
  /**
   * @var KickOff
   */
  protected $mainApplication;

  protected $displayTitle = "Kickoff Test";

  /**
  * @var Logger
  */
  protected $logger = null;

  protected $logs = [
    array('name' => 'junit-file', 'shortcut' => 'j', 'mode' => InputOption::VALUE_OPTIONAL, 'description' => 'path to a junit compatible log file'),
    array('name' => 'csv-file', 'shortcut' => 'c', 'mode' => InputOption::VALUE_OPTIONAL, 'description' => 'path to a csv log file'),
  ];

  protected function configure()
  {
    foreach ($this->logs as $log) {
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
    $this->handleLogOptions($input);

    $output->writeln($this->displayTitle);
    $output->writeln('');
  }

  protected function handleLogOptions(InputInterface $input)
  {
    $listenerFactory = new ListenerFactory();

    foreach ($this->logs as $log) {
      $logPath = $input->getOption($log['name']);
      if (empty($logPath)) {
        continue;
      }

      $logListener = $listenerFactory->get($log['name']);
      $logListener->logFileName = $logPath;
      $this->logger->addListener($log['name'], $logListener);
    }
  }

  public function setMainApplication(KickOff $mainApplication)
  {
    $this->mainApplication = $mainApplication;
  }

  public function setDisplayTitle($displayTitle=null)
  {
      if (!is_null($displayTitle)) {
        $this->displayTitle = $displayTitle;
      }

      return $this;
  }

  /**
   * @param Logger $logger
   */
  public function setLogger(Logger $logger)
  {
    $this->logger = $logger;
  }
}
