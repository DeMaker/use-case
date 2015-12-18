<?php

namespace tests\DeSmart\DeMaker\UseCase\Schema;

class CommandHandlerBuildStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    private $buildStrategy;

    private $fqn = 'Bar\Foo\WatCommand';

    private $expectedCommandFqn = 'Bar\Foo\WatCommand';

    private $expectedResponseFqn = 'Bar\Foo\WatCommandResponse';

    private $expectedHandlerFqn = 'Bar\Foo\WatCommandHandler';

    public function setUp()
    {
        $this->input = $this->prophesize(\Symfony\Component\Console\Input\InputInterface::class);
        $this->input->getArgument('fqn')->willReturn($this->fqn);
        $this->input->hasOption('outputProperties')->willReturn(true);
        $this->input->getOption('outputProperties')->willReturn('firstname:string,lastname:string,dob:\Carbon\Carbon');

        $this->buildStrategy = new \DeSmart\DeMaker\UseCase\Schema\CommandHandlerBuildStrategy($this->input->reveal());
    }

    /** @test */
    public function it_is_initializable()
    {
        $this->assertInstanceOf(\DeSmart\DeMaker\UseCase\Schema\CommandHandlerBuildStrategy::class, $this->buildStrategy);
    }

    /** @test */
    public function it_implements_build_strategy_interface()
    {
        $this->assertInstanceOf(\DeSmart\DeMaker\Core\Schema\BuildStrategyInterface::class, $this->buildStrategy);
    }

    /** @test */
    public function it_makes_handler_schema()
    {
        /** @var \Memio\Model\Object $handler */
        $handler = $this->buildStrategy->make()[0];

        $this->assertInstanceOf(\Memio\Model\Object::class, $handler);
    }

    /** @test */
    public function it_makes_handler_with_defined_fully_qualified_name()
    {
        /** @var \Memio\Model\Object $handler */
        $handler = $this->buildStrategy->make()[0];

        $this->assertEquals($this->expectedHandlerFqn, $handler->getFullyQualifiedName());
    }

    /** @test */
    public function it_makes_handler_which_handles_command()
    {
        /** @var \Memio\Model\Object $handler */
        $handler = $this->buildStrategy->make()[0];

        $methods = $handler->allMethods();

        /** @var \Memio\Model\Method $handle */
        $handle = array_shift($methods);
        $this->assertEquals('handle', $handle->getName());
        $this->assertEquals('public', $handle->getVisibility());

        /** @var \Memio\Model\PhpDoc\ReturnTag $phpDocReturnTag */
        $phpDocReturnTag = $handle->getPhpdoc()->getReturnTag();
        $this->assertEquals('\\' . $this->expectedResponseFqn, $phpDocReturnTag->getType());

        /** @var \Memio\Model\PhpDoc\ParameterTag  $phpDocParamTag */
        $phpDocParamTag = array_shift($handle->getPhpdoc()->getParameterTags());
        $this->assertEquals('\\' . $this->expectedCommandFqn, $phpDocParamTag->getType());
        $this->assertEquals('command', $phpDocParamTag->getName());

        $arguments = $handle->allArguments();
        $this->assertCount(1, $arguments);

        /** @var \Memio\Model\Argument $command */
        $command = array_shift($arguments);
        $this->assertEquals('\\' . $this->expectedCommandFqn, $command->getType());
        $this->assertEquals('command', $command->getName());

        $handlerBody = "        return new \\{$this->expectedResponseFqn}(\$firstname, \$lastname, \$dob);";

        $this->assertEquals($handlerBody, $handle->getBody());
    }
}
