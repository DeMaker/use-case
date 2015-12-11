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
        $this->application = new Application;
    }

    public function registerCommands()
    {
        $this->application->add(new Make);
    }

    public function run()
    {
        $this->registerCommands();

        $this->application->run();
    }
}

$boot = new Boot;
$boot->run();
