#!/usr/bin/env php
<?php
use Frickelbruder\KickOff\Cli\Commands\DefaultCommand;
use Frickelbruder\KickOff\Configuration\Configuration;
use Frickelbruder\KickOff\Http\HttpRequester;
use Frickelbruder\KickOff\Log\Listener\ConsoleOutputListener;
use Frickelbruder\KickOff\Log\Logger;
use Frickelbruder\KickOff\Yaml\Yaml;
use Symfony\Component\Console\Application;

foreach (array(__DIR__ . '/../../autoload.php', __DIR__ . '/../vendor/autoload.php', __DIR__ . '/vendor/autoload.php') as $file) {
    if (file_exists($file)) {
        require $file;
    }
}



$yaml = new Yaml();
$config = new Configuration($yaml);
$requester = new HttpRequester();
$logger = new Logger();
$logger->addListener('console', new ConsoleOutputListener());
$command = new DefaultCommand('run', $requester, $config, $logger);
$app = new Application("KickOff", "1.0.0");
$app->add($command);
$app->run();
