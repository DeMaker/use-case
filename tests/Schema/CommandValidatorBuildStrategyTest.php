<?php

namespace tests\DeSmart\DeMaker\UseCase\Schema;

class CommandValidatorBuildStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    private $buildStrategy;

    private $fqn = 'Bar\Foo\WatCommand';
    private $expectedCommandFqn = 'Bar\Foo\WatCommand';
    private $expectedCommandValidatorFqn = 'Bar\Foo\WatCommandValidator';

    public function setUp()
    {
        $this->input = $this->prophesize(\Symfony\Component\Console\Input\InputInterface::class);
        $this->input->getArgument('fqn')->willReturn($this->fqn);

        $this->buildStrategy = new \DeSmart\DeMaker\UseCase\Schema\CommandValidatorBuildStrategy($this->input->reveal());
    }

    /** @test */
    public function it_is_initializable()
    {
        $this->assertInstanceOf(\DeSmart\DeMaker\UseCase\Schema\CommandValidatorBuildStrategy::class, $this->buildStrategy);
    }

    /** @test */
    public function it_implements_build_strategy_interface()
    {
        $this->assertInstanceOf(\DeSmart\DeMaker\Core\Schema\BuildStrategyInterface::class, $this->buildStrategy);
    }

    /** @test */
    public function it_makes_handler_schema()
    {
        /** @var \Memio\Model\Object $validator */
        $validator = $this->buildStrategy->make()[0];

        $this->assertInstanceOf(\Memio\Model\Object::class, $validator);
    }

    /** @test */
    public function it_makes_validator_with_defined_fully_qualified_name()
    {
        /** @var \Memio\Model\Object $validator */
        $validator = $this->buildStrategy->make()[0];

        $this->assertEquals($this->expectedCommandValidatorFqn, $validator->getFullyQualifiedName());
    }

    /** @test */
    public function it_makes_handler_which_validates_command()
    {
        /** @var \Memio\Model\Object $validator */
        $validator = $this->buildStrategy->make()[0];

        $methods = $validator->allMethods();

        /** @var \Memio\Model\Method $validate */
        $validate = array_shift($methods);
        $this->assertEquals('validate', $validate->getName());
        $this->assertEquals('public', $validate->getVisibility());

        /** @var \Memio\Model\PhpDoc\ReturnTag $phpDocReturnTag */
        $phpDocReturnTag = $validate->getPhpdoc()->getReturnTag();
        $this->assertEquals('void', $phpDocReturnTag->getType());

        /** @var \Memio\Model\PhpDoc\ParameterTag  $phpDocParamTag */
        $phpDocParamTag = array_shift($validate->getPhpdoc()->getParameterTags());
        $this->assertEquals('\\' . $this->expectedCommandFqn, $phpDocParamTag->getType());
        $this->assertEquals('command', $phpDocParamTag->getName());

        $arguments = $validate->allArguments();
        $this->assertCount(1, $arguments);

        /** @var \Memio\Model\Argument $command */
        $command = array_shift($arguments);
        $this->assertEquals('\\' . $this->expectedCommandFqn, $command->getType());
        $this->assertEquals('command', $command->getName());
    }

}
