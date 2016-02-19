<?php

namespace tests\DeSmart\DeMaker\Core\Schema;

class DTOUnitTestBuildStrategyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Symfony\Component\Console\Input\InputInterface
     */
    private $input;

    private $buildStrategy;

    private $fqn = 'Bar\Foo\Wat';

    private $testfqn = 'tests\Foo\WatTest';

    public function setUp()
    {
        $this->input = $this->prophesize(\Symfony\Component\Console\Input\InputInterface::class);
        $this->input->getArgument('fqn')->willReturn($this->fqn);
        $this->input->getArgument('testfqn')->willReturn($this->testfqn);
        $this->input->hasOption('inputProperties')->willReturn(true);
        $this->input->getOption('inputProperties')->willReturn('firstname:string,lastname:string,dob:\Carbon\Carbon');

        $this->buildStrategy = new \DeSmart\DeMaker\Core\Schema\DTOUnitTestBuildStrategy($this->input->reveal());
    }

    /** @test */
    public function it_is_initalizable()
    {
        $this->assertInstanceOf(\DeSmart\DeMaker\Core\Schema\DTOUnitTestBuildStrategy::class, $this->buildStrategy);
    }

    /** @test */
    public function it_implements_build_strategy_interface()
    {
        $this->assertInstanceOf(\DeSmart\DeMaker\Core\Schema\BuildStrategyInterface::class, $this->buildStrategy);
    }

    /** @test */
    public function it_makes_dto_unit_test_schema()
    {
        /** @var \Memio\Model\Object $dtoUnitTest */
        $dtoUnitTest = $this->buildStrategy->make()[0];

        $this->assertInstanceOf(\Memio\Model\Object::class, $dtoUnitTest);
    }

    /** @test */
    public function it_makes_dto_unit_test_with_defined_test_fully_qualified_name()
    {
        /** @var \Memio\Model\Object $dtoUnitTest */
        $dtoUnitTest = $this->buildStrategy->make()[0];

        $this->assertEquals($this->testfqn, $dtoUnitTest->getFullyQualifiedName());
    }

    /** @test */
    public function it_makes_dto_unit_test_with_defined_properties()
    {
        /** @var \Memio\Model\Object $dto */
        $dto = $this->buildStrategy->make()[0];
        $properties = $dto->allProperties();

        /** @var \Memio\Model\Property $firstname */
        $firstname = array_shift($properties);
        $this->assertEquals('firstname', $firstname->getName());
        $this->assertEquals('private', $firstname->getVisibility());
        $this->assertEquals('string', $firstname->getPhpdoc()->getVariableTag()->getType());

        /** @var \Memio\Model\Property $lastname */
        $lastname = array_shift($properties);
        $this->assertEquals('lastname', $lastname->getName());
        $this->assertEquals('private', $lastname->getVisibility());
        $this->assertEquals('string', $lastname->getPhpdoc()->getVariableTag()->getType());

        /** @var \Memio\Model\Property $dob */
        $dob = array_shift($properties);
        $this->assertEquals('dob', $dob->getName());
        $this->assertEquals('private', $dob->getVisibility());
        $this->assertEquals('\Carbon\Carbon', $dob->getPhpdoc()->getVariableTag()->getType());
    }

}