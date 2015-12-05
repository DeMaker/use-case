<?php

namespace tests;

use Fojuth\Stamp\Template\Exception\DefinitionNotFoundException;
use Fojuth\Stamp\Template\Definition;
use Fojuth\Stamp\Template\DefinitionFactory;

class DefinitionFactoryTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_creates_a_definition()
    {
        $definitions = [
            'foo' => [
                'alias' => 'foo',
                'template' => 'Foo.php',
            ],
        ];

        $factory = new DefinitionFactory($definitions);

        $definition = $factory->getDefinition('foo');

        $this->assertInstanceOf(Definition::class, $definition);
    }

    /**
     * @test
     */
    public function it_throws_exception_if_definition_not_found()
    {
        $factory = new DefinitionFactory([]);

        $this->setExpectedException(DefinitionNotFoundException::class);

        $factory->getDefinition('foo');
    }
}
