<?php

require 'vendor/autoload.php';

use DeSmart\DeMaker\Core\Command\Make;
use Symfony\Component\Console\Application;

class Boot
{

    /**
     * @var Application
     */
    protected $app;

    public function __construct()
    {
        $this->app = new Application;
    }

    public function registerCommands()
    {
        $this->app->add(new Make);
    }

    public function run()
    {
        $this->registerCommands();

        $this->app->run();
    }
}

$boot = new Boot;
$boot->run();
