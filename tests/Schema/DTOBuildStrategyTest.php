<?php

namespace tests\DeSmart\DeMaker\Core\Schema;

class DTOBuildStrategyTest extends \PHPUnit_Framework_TestCase
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

        $this->buildStrategy = new \DeSmart\DeMaker\Core\Schema\DTOBuildStrategy($this->input->reveal());
    }

    /** @test */
    public function it_is_initalizable()
    {
        $this->assertInstanceOf(\DeSmart\DeMaker\Core\Schema\DTOBuildStrategy::class, $this->buildStrategy);
    }

    /** @test */
    public function it_implements_build_strategy_interface()
    {
        $this->assertInstanceOf(\DeSmart\DeMaker\Core\Schema\BuildStrategyInterface::class, $this->buildStrategy);
    }

    /** @test */
    public function it_makes_command_schema()
    {
        /** @var \Memio\Model\Object $command */
        $command = $this->buildStrategy->make()[0];

        $this->assertInstanceOf(\Memio\Model\Object::class, $command);
    }

    /** @test */
    public function it_makes_command_with_defined_fully_qualified_name()
    {
        /** @var \Memio\Model\Object $command */
        $command = $this->buildStrategy->make()[0];

        $this->assertEquals($this->fqn, $command->getFullyQualifiedName());
    }

    /** @test */
    public function it_makes_command_with_defined_properties()
    {
        /** @var \Memio\Model\Object $command */
        $command = $this->buildStrategy->make()[0];
        $properties = $command->allProperties();

        /** @var \Memio\Model\Property $firstname */
        $firstname = array_shift($properties);
        $this->assertEquals('firstname', $firstname->getName());
        $this->assertEquals('private', $firstname->getVisibility());

        /** @var \Memio\Model\Property $lastname */
        $lastname = array_shift($properties);
        $this->assertEquals('lastname', $lastname->getName());
        $this->assertEquals('private', $lastname->getVisibility());

        /** @var \Memio\Model\Property $dob */
        $dob = array_shift($properties);
        $this->assertEquals('dob', $dob->getName());
        $this->assertEquals('private', $dob->getVisibility());
    }

    /** @test */
    public function it_makes_command_initialized_with_defined_properties()
    {
        /** @var \Memio\Model\Object $command */
        $command = $this->buildStrategy->make()[0];
        $methods = $command->allMethods();

        /** @var \Memio\Model\Method $construct */
        $construct = array_shift($methods);
        $this->assertEquals('__construct', $construct->getName());
        $this->assertEquals('public', $construct->getVisibility());

        $constructorArguments = $construct->allArguments();

        /** @var \Memio\Model\Argument $firstname */
        $firstname = array_shift($constructorArguments);
        $this->assertEquals('firstname', $firstname->getName());
        $this->assertEquals('string', $firstname->getType());

        /** @var \Memio\Model\Argument $lastname */
        $lastname = array_shift($constructorArguments);
        $this->assertEquals('lastname', $lastname->getName());
        $this->assertEquals('string', $lastname->getType());

        /** @var \Memio\Model\Argument $dob */
        $dob = array_shift($constructorArguments);
        $this->assertEquals('dob', $dob->getName());
        $this->assertEquals('\Carbon\Carbon', $dob->getType());

        $expectedConstructBody = "\$this->firstname = \$firstname;\n\$this->lastname = \$lastname;\n\$this->dob = \$dob;";
        $this->assertEquals($expectedConstructBody, $construct->getBody());
    }

    /** @test */
    public function it_makes_command_with_getters_for_defined_properties()
    {
        /** @var \Memio\Model\Object $command */
        $command = $this->buildStrategy->make()[0];
        $methods = $command->allMethods();

        /** @var \Memio\Model\Method $construct */
        $construct = array_shift($methods);

        /** @var \Memio\Model\Method $getFirstname */
        $getFirstname = array_shift($methods);
        $this->assertEquals('getFirstname', $getFirstname->getName());
        $this->assertEquals('public', $getFirstname->getVisibility());

        /** @var \Memio\Model\Method $getLastname */
        $getLastname = array_shift($methods);
        $this->assertEquals('getLastname', $getLastname->getName());
        $this->assertEquals('public', $getLastname->getVisibility());

        /** @var \Memio\Model\Method $getDob */
        $getDob = array_shift($methods);
        $this->assertEquals('getDob', $getDob->getName());
        $this->assertEquals('public', $getDob->getVisibility());
    }
}