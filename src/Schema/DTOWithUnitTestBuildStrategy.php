<?php

namespace DeSmart\DeMaker\Core\Schema;

use Symfony\Component\Console\Input\InputInterface;

class DTOWithUnitTestBuildStrategy implements BuildStrategyInterface
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @param InputInterface $input
     */
    public function __construct(InputInterface $input)
    {
        $this->input = $input;
    }

    /**
     * @return Object[]
     */
    public function make()
    {
        return array_merge(
            (new DTOBuildStrategy($this->input))->make(),
            (new DTOUnitTestBuildStrategy($this->input))->make()
        );
    }
}