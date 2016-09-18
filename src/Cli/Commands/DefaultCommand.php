<?php
namespace Frickelbruder\KickOff\Cli\Commands;

use Frickelbruder\KickOff\Configuration\Configuration;
use Frickelbruder\KickOff\Configuration\Section;
use Frickelbruder\KickOff\Http\HttpRequester;
use Frickelbruder\KickOff\Log\Listener\JunitLogListener;
use Frickelbruder\KickOff\Log\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DefaultCommand extends Command {

    /**
     * @var Configuration
     */
    protected $configuration = null;

    /**
     * @var HttpRequester
     */
    protected $httpRequester = null;

    /**
     * @var Logger
     */
    protected $logger = null;

    protected $errorCount = 0;

    public function __construct($name = null, $httpRequester, $configuration, $logger) {
        parent::__construct( $name );
        $this->configuration = $configuration;
        $this->httpRequester = $httpRequester;
        $this->logger = $logger;
    }

    /**
     * @param HttpRequester $httpRequester
     */
    public function setHttpRequester($httpRequester) {
        $this->httpRequester = $httpRequester;
    }

    protected function configure()
    {
        $this
            ->setName('run')
            ->setDescription('Runs your kickoff')
            ->addArgument(
                'config-file',
                InputArgument::REQUIRED,
                'the config file to use'
            )->addOption(
                'junit-file',
                'j',
                InputOption::VALUE_OPTIONAL,
                'path to a junit compatible log file'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("Kickoff Test");
        $output->writeln("");

        $this->handleJunitOption( $input->getOption('junit-file') );
        $this->buildConfiguration($input->getArgument('config-file'));

        foreach($this->configuration->getSections() as $section) {
            $this->handleSection($section);
        }
        $this->logger->finish();

        return $this->errorCount;
    }

    protected function buildConfiguration($configFile) {
        $this->configuration->build($configFile);
    }

    protected function handleSection(Section $section) {
        $url = $section->getTargetUrl();
        $page = $this->fetchPage($url);
        $rules = $section->getRules();

        foreach($rules as $rule) {
            $rule->setHttpResponse($page);
            $result = $rule->validate();
            if(!$result) {
                $this->errorCount++;
            }

            $this->logger->log($section->getName(), $section->getTargetUrl()->getUrl(), $rule, $result);
        }
    }

    protected function fetchPage($url) {
        return $this->httpRequester->request($url);
    }

    /**
     * @param string
     */
    protected function handleJunitOption($option) {
        if( !empty( $option ) ) {

            $junitLogListener = new JunitLogListener();
            $junitLogListener->logFileName = $option;
            $this->logger->addListener( 'junit', $junitLogListener );

        }
    }

}