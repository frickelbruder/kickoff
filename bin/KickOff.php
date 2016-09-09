#!/usr/bin/env php
<?php
use Frickelbruder\KickOff\Cli\Commands\DefaultCommand;
use Frickelbruder\KickOff\Configuration\Configuration;
use Frickelbruder\KickOff\Log\Listener\ConsoleOutputListener;

require __DIR__.'/../vendor/autoload.php';

$yaml = new \Frickelbruder\KickOff\Yaml\Yaml();
$config = new Configuration($yaml);
$requester = new \Frickelbruder\KickOff\Http\HttpRequester();
$logger = new \Frickelbruder\KickOff\Log\Logger();
$logger->addListener('console', new ConsoleOutputListener());
$command = new DefaultCommand('run', $requester, $config, $logger);
$app = new \Symfony\Component\Console\Application("KickOff", "1.0.0");
$app->add($command);
$app->run();
