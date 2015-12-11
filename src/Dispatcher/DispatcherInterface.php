<?php

namespace DeSmart\DeMaker\Core\Dispatcher;

use DeSmart\DeMaker\Schema\BuildStrategyInterface;

interface DispatcherInterface
{

    /**
     * @param BuildStrategyInterface $buildStrategy
     */
    public function __construct(BuildStrategyInterface $buildStrategy);

    /**
     * @return array
     */
    public function run();
}
