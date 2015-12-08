<?php

namespace Fojuth\Stamp;

use Fojuth\Stamp\Config\Loader;

class Dispatcher
{
    protected $alias;
    protected $fqn;

    public function __construct()
    {
        $this->loadConfig();
    }

    public function run($alias, $fqn)
    {
        $this->alias = $alias;
        $this->fqn = $fqn;

        var_dump('woot', $alias, $fqn);
        die;
    }

    protected function loadConfig()
    {
        if (false === file_exists(__DIR__ . 'stamp.json')) {
            throw new \LogicException('stamp.json not found');
        }

        $loader = new Loader();
    }
}
