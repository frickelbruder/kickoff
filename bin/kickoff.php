#!/usr/bin/env php
<?php

use Frickelbruder\KickOff\App\KickOff;
use Frickelbruder\KickOff\Cli\Commands\DefaultCommand;
use Frickelbruder\KickOff\Cli\Commands\SeoCheckCommand;
use Frickelbruder\KickOff\Configuration\Configuration;
use Frickelbruder\KickOff\Http\HttpRequester;
use Frickelbruder\KickOff\Log\Listener\ConsoleOutputListener;
use Frickelbruder\KickOff\Log\Logger;
use Frickelbruder\KickOff\Yaml\Yaml;
use Symfony\Component\Console\Application;

foreach (array(__DIR__ . '/../../../autoload.php', __DIR__ . '/../../autoload.php', __DIR__ . '/../autoload.php', __DIR__ . '/../vendor/autoload.php', __DIR__ . '/vendor/autoload.php') as $file) {
    if (file_exists($file)) {
        require $file;
        break;
    }
}




$yaml = new Yaml();
$config = new Configuration($yaml);
$requester = new HttpRequester();
$logger = new Logger();
$logger->addListener('console', new ConsoleOutputListener());
$kickoff = new KickOff();
$kickoff->setConfiguration($config);
$kickoff->setLogger($logger);
$kickoff->setHttpRequester($requester);

$app = new Application("KickOff", "1.0.0");

$command = new DefaultCommand('run');
$command->setMainApplication($kickoff);
$command->setLogger($logger);
$app->add($command);

$command = new SeoCheckCommand('seocheck');
$command->setMainApplication($kickoff);
$app->add($command);

$app->run();
