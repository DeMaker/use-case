<?php

namespace tests;

use DeSmart\DeMaker\Core\Template\Definition;

class DefinitionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_has_proper_getters()
    {
        $definitionArray = [
            'alias' => 'foo',
            'template' => 'Foo.php',
            'cascade' => ['bar, baz'],
            'test-template' => 'TestFoo.php',
        ];

        $definition = new Definition($definitionArray);

        $this->assertEquals($definitionArray['alias'], $definition->getAlias());
        $this->assertEquals($definitionArray['template'], $definition->getTemplateFile());
        $this->assertEquals($definitionArray['cascade'], $definition->getCascade());
        $this->assertEquals($definitionArray['test-template'], $definition->getTestTemplateFile());
    }
}
