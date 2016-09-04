#!/usr/bin/env php
<?php
use Frickelbruder\KickOff\Cli\Commands\DefaultCommand;
use Frickelbruder\KickOff\Configuration\Configuration;

require __DIR__.'/../vendor/autoload.php';

$config = new Configuration();
$requester = new \Frickelbruder\KickOff\Http\HttpRequester();
$command = new DefaultCommand('run', $requester, $config);
$app = new \Symfony\Component\Console\Application("KickOff", "1.0.0");
$app->add($command);
$app->run();
