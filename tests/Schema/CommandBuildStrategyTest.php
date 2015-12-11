<?php

namespace tests\DeSmart\DeMaker\UseCase\Schema;

class CommandBuildStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    private $buildStrategy;

    private $fqn = 'Bar\Foo\WatCommand';

    public function setUp()
    {
        $this->input = $this->prophesize(\Symfony\Component\Console\Input\InputInterface::class);
        $this->input->getArgument('fqn')->willReturn($this->fqn);
        $this->input->hasOption('inputProperties')->willReturn(true);
        $this->input->getOption('inputProperties')->willReturn('firstname:string,lastname:string,dob:\Carbon\Carbon');

        $this->buildStrategy = new \DeSmart\DeMaker\UseCase\Schema\CommandBuildStrategy($this->input->reveal());
    }

    /** @test */
    public function it_is_initializable()
    {
        $this->assertInstanceOf(\DeSmart\DeMaker\UseCase\Schema\CommandBuildStrategy::class, $this->buildStrategy);
    }

    /** @test */
    public function it_is_dto_build_strategy()
    {
        $this->assertInstanceOf(\DeSmart\DeMaker\Core\Schema\DTOBuildStrategy::class, $this->buildStrategy);
    }
}
