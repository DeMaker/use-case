<?php

namespace DeSmart\DeMaker\Core\Dispatcher;

use DeSmart\DeMaker\Core\Schema\BuildStrategyInterface;

interface DispatcherInterface
{

    /**
     * @param BuildStrategyInterface $buildStrategy
     */
    public function __construct(BuildStrategyInterface $buildStrategy);

    /**
     * @return DispatcherResponse
     */
    public function run();
}
