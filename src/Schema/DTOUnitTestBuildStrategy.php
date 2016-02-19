<?php

namespace DeSmart\DeMaker\Core\Schema;

use Memio\Model\Object;
use Symfony\Component\Console\Input\InputInterface;

class DTOUnitTestBuildStrategy implements BuildStrategyInterface
{

    /**
     * @var string
     */
    protected $fqn;

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @param InputInterface $input
     */
    public function __construct(InputInterface $input)
    {
        $this->fqn = $input->getArgument('testfqn');
        $properties = $input->getOption('inputProperties');

        if (false === is_null($properties)) {
            $this->properties = explode(',', $input->getOption('inputProperties'));
        }
    }

    /**
     * @return Object[]
     */
    public function make()
    {
        $phpunitTestCase = Object::make(\PHPUnit_Framework_TestCase::class);
        $dtoUnitTest = Object::make($this->fqn);
        $dtoUnitTest->extend($phpunitTestCase);

        return [$dtoUnitTest];
    }
}