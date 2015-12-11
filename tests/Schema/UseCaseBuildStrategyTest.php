<?php

namespace tests\DeSmart\DeMaker\UseCase\Schema;

class UseCaseBuildStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    private $buildStrategy;

    private $fqn = 'Bar\Foo\WatCommand';
    private $expectedCommandFqn = 'Bar\Foo\WatCommand';
    private $expectedCommandResponseFqn = 'Bar\Foo\WatCommandResponse';
    private $expectedCommandHandlerFqn = 'Bar\Foo\WatCommandHandler';


    public function setUp()
    {
        $this->input = $this->prophesize(\Symfony\Component\Console\Input\InputInterface::class);
        $this->input->getArgument('fqn')->willReturn($this->fqn);
        $this->input->hasOption('inputProperties')->willReturn(true);
        $this->input->getOption('inputProperties')->willReturn('firstname:string,lastname:string,dob:\Carbon\Carbon');
        $this->input->hasOption('outputProperties')->willReturn(true);
        $this->input->getOption('outputProperties')->willReturn('person:Person');

        $this->buildStrategy = new \DeSmart\DeMaker\UseCase\Schema\UseCaseBuildStrategy($this->input->reveal());
    }

    /** @test */
    public function it_is_initializable()
    {
        $this->assertInstanceOf(\DeSmart\DeMaker\UseCase\Schema\UseCaseBuildStrategy::class, $this->buildStrategy);
    }

    /** @test */
    public function it_is_build_strategy()
    {
        $this->assertInstanceOf(\DeSmart\DeMaker\Core\Schema\BuildStrategyInterface::class, $this->buildStrategy);
    }
    
    /** @test */
    public function it_makes_command_and_handler_and_response()
    {
        $response = $this->buildStrategy->make();

        /** @var \Memio\Model\Object $command */
        $command = array_shift($response);
        $this->assertEquals($this->expectedCommandFqn, $command->getFullyQualifiedName());

        /** @var \Memio\Model\Object $commandHandler */
        $commandHandler = array_shift($response);
        $this->assertEquals($this->expectedCommandHandlerFqn, $commandHandler->getFullyQualifiedName());

        /** @var \Memio\Model\Object $commandResponse */
        $commandResponse = array_shift($response);
        $this->assertEquals($this->expectedCommandResponseFqn, $commandResponse->getFullyQualifiedName());
    }
}
