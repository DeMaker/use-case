<?php

namespace Fojuth\Stamp;

use Fojuth\Stamp\Config\Loader;
use Fojuth\Stamp\Locator\TemplateLocator;
use Fojuth\Stamp\Output\Writer;
use Fojuth\Stamp\Template\Builder;
use Fojuth\Stamp\Template\DefinitionFactory;

/**
 * Dispatcher responsible for running Stamp.
 */
class Dispatcher
{

    /**
     * @var array
     */
    protected $results = [];

    public function run($alias, $fqn)
    {
        $config = $this->getConfig();

        $declaration = new Declaration($alias, $fqn);

        $templateLocator = $this->getTemplateLocator($config, $declaration);

        $builder = $this->getBuilder($declaration, $templateLocator);

        $writer = new Writer($config);
        $writer->makeClass($declaration, $builder->make());

        $this->results[] = [
            'declaration' => $declaration,
            'path' => $writer->getCompiledFilePath(),
        ];

        return $this->results;
    }

    /**
     * @return Loader
     */
    protected function getConfig()
    {
        if (false === file_exists('stamp.json')) {
            throw new \LogicException('stamp.json not found');
        }

        return new Loader(file_get_contents('stamp.json'));
    }

    /**
     * @param Loader $config
     * @param Declaration $declaration
     * @return TemplateLocator
     */
    protected function getTemplateLocator(Loader $config, Declaration $declaration)
    {
        $definitionFactory = new DefinitionFactory($config->getDefinitions());

        $templateLocator = new TemplateLocator($config, $definitionFactory);
        $templateLocator->setDeclaration($declaration);

        return $templateLocator;
    }

    /**
     * @param Declaration $declaration
     * @param TemplateLocator $templateLocator
     * @return Builder
     */
    protected function getBuilder(Declaration $declaration, TemplateLocator $templateLocator)
    {
        $builder = new Builder();
        $builder->setDeclaration($declaration);
        $builder->setTemplateContent($templateLocator->fetchTemplateContent());

        return $builder;
    }
}
