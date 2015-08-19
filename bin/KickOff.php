#!/usr/bin/env php
<?php
use frickelbruder\KickOff\Cli\Commands\DefaultCommand;

require __DIR__.'/../vendor/autoload.php';

$app = new \Symfony\Component\Console\Application("KickOff", "1.0.0");
$app->add(new DefaultCommand());
$app->run();
