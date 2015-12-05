<?php

namespace Fojuth\Stamp\Locator;

use Fojuth\Stamp\Config\Loader;
use Fojuth\Stamp\Declaration;
use Fojuth\Stamp\Locator\Exception\TemplateNotFoundException;
use Fojuth\Stamp\Template\DefinitionFactory;

class TemplateLocator
{

    /**
     * @var Declaration
     */
    protected $declaration;

    /**
     * @var Loader
     */
    private $config;

    /**
     * @var DefinitionFactory
     */
    private $factory;

    public function __construct(Loader $config, DefinitionFactory $factory)
    {
        $this->config = $config;
        $this->factory = $factory;
    }

    public function setDeclaration(Declaration $declaration)
    {
        $this->declaration = $declaration;

        return $this;
    }

    public function fetchTemplateContent()
    {
        $alias = $this->declaration->getAlias();
        $templateDir = $this->config->getTemplateDir();
        $definition = $this->factory->getDefinition($alias);
        $path = $templateDir . '/' . $definition->getTemplateFile();

        if (false === file_exists($path)) {
            throw new TemplateNotFoundException;
        }

        return file_get_contents($path);
    }
}
