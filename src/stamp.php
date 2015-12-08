<?php

require __DIR__ . '/../vendor/autoload.php';

use Fojuth\Stamp\Command\Make;
use Symfony\Component\Console\Application;

$application = new Application;
$application->add(new Make);
$application->run();
