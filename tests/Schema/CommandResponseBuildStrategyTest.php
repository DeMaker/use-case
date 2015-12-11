<?php

namespace tests\DeSmart\DeMaker\UseCase\Schema;

class CommandResponseBuildStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    private $buildStrategy;

    private $fqn = 'Bar\Foo\WatCommand';

    private $expectedResponseFqn = 'Bar\Foo\WatCommandResponse';

    public function setUp()
    {
        $this->input = $this->prophesize(\Symfony\Component\Console\Input\InputInterface::class);
        $this->input->getArgument('fqn')->willReturn($this->fqn);
        $this->input->hasOption('outputProperties')->willReturn(true);
        $this->input->getOption('outputProperties')->willReturn('firstname:string,lastname:string,dob:\Carbon\Carbon');

        $this->buildStrategy = new \DeSmart\DeMaker\UseCase\Schema\CommandResponseBuildStrategy($this->input->reveal());
    }

    /** @test */
    public function it_is_initializable()
    {
        $this->assertInstanceOf(\DeSmart\DeMaker\UseCase\Schema\CommandResponseBuildStrategy::class, $this->buildStrategy);
    }

    /** @test */
    public function it_is_dto_build_strategy()
    {
        $this->assertInstanceOf(\DeSmart\DeMaker\Core\Schema\DTOBuildStrategy::class, $this->buildStrategy);
    }

    /** @test */
    public function it_makes_command_with_defined_fully_qualified_name()
    {
        /** @var \Memio\Model\Object $command */
        $command = $this->buildStrategy->make();

        $this->assertEquals($this->expectedResponseFqn, $command->getFullyQualifiedName());
    }
}
