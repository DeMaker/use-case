<?php

namespace tests;

use Fojuth\Stamp\Config\Loader;
use Fojuth\Stamp\Declaration;
use Fojuth\Stamp\Locator\TemplateLocator;
use Fojuth\Stamp\Template\Definition;
use Fojuth\Stamp\Template\DefinitionFactory;
use Fojuth\Stamp\Locator\Exception\TemplateNotFoundException;

class TemplateLocatorTest extends \PHPUnit_Framework_TestCase
{
    protected $templateFile = 'Foo/Bar/Baz.php';
    protected $templateContent = 'foo bar';

    public function setUp()
    {
        mkdir('Foo/Bar', 0777, true);
        file_put_contents($this->templateFile, $this->templateContent);
    }

    public function tearDown()
    {
        unlink($this->templateFile);
        rmdir('Foo/Bar');
        rmdir('Foo');
    }

    /**
     * @test
     */
    public function it_locates_a_template()
    {
        $declaration = new Declaration('foo', 'Foo\Bar\Baz');
        $definition = new Definition(['alias' => 'foo', 'template' => 'Baz.php']);

        $config = $this->prophesize(Loader::class);
        $config->getTemplateDir()->willReturn('Foo/Bar');

        $factory = $this->prophesize(DefinitionFactory::class);
        $factory->getDefinition('foo')->willReturn($definition);

        $locator = new TemplateLocator($config->reveal(), $factory->reveal());
        $locator->setDeclaration($declaration);

        $this->assertEquals($this->templateContent, $locator->fetchTemplateContent());
    }

    /**
     * @test
     */
    public function it_throws_exception_if_template_not_found()
    {
        $declaration = new Declaration('foo', 'Foo\Bar\Nope');
        $definition = new Definition(['alias' => 'foo', 'template' => 'Nope.php']);

        $config = $this->prophesize(Loader::class);
        $config->getTemplateDir()->willReturn('Foo/Bar');

        $factory = $this->prophesize(DefinitionFactory::class);
        $factory->getDefinition('foo')->willReturn($definition);

        $locator = new TemplateLocator($config->reveal(), $factory->reveal());
        $locator->setDeclaration($declaration);

        $this->setExpectedException(TemplateNotFoundException::class);

        $locator->fetchTemplateContent();
    }
}
