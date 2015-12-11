<?php

namespace DeSmart\DeMaker\Schema;

use Memio\Model\Object;
use Symfony\Component\Console\Input\InputInterface;

interface BuildStrategyInterface
{

    /**
     * @param InputInterface $input
     */
    public function __construct(InputInterface $input);

    /**
     * @return Object[]
     */
    public function make();
}
