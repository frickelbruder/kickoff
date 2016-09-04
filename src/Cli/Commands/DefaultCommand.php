<?php
namespace Frickelbruder\KickOff\Cli\Commands;

use Frickelbruder\KickOff\Configuration\Configuration;
use Frickelbruder\KickOff\Configuration\Section;
use Frickelbruder\KickOff\Http\HttpRequester;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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

    public function __construct($name = null, $httpRequester = null, $configuration = null) {
        parent::__construct( $name );
        $this->configuration = $configuration;
        $this->httpRequester = $httpRequester;
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
                'configfile',
                InputArgument::REQUIRED,
                'the config file to use'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->buildConfiguration($input->getArgument('configfile'));

        foreach($this->configuration->getSections() as $sectionName => $section) {

            $result = $this->handleSection($sectionName, $section);
            $output->writeln($result);

        }

    }

    protected function buildConfiguration($configFile) {
        $this->configuration->buildFromFile($configFile);
    }

    protected function handleSection($sectionname, Section $section) {
        $url = $section->getTargetUrl();
        $page = $this->fetchPage($url);
        $rules = $section->getRules();

        foreach($rules as $rule) {
            $rule->setItemToValidate($page);
            $result = $rule->validate();
            echo $sectionname . ':' . ((int) $result);
        }
    }

    protected function fetchPage($url) {
        return $this->httpRequester->request($url);
    }

}