<?php

use Fojuth\Stamp\Definition;

class DefinitionTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_has_proper_setters_and_getters()
    {
        $def = new Definition($alias = 'foo', $fqn = 'Foo\Bar');
        $def->setCascade(false);
        $def->setGenerateTests(false);

        $this->assertEquals($alias, $def->getAlias());
        $this->assertEquals($fqn, $def->getFqn());
        $this->assertFalse($def->generateTests());
        $this->assertFalse($def->isCascade());
    }
}
