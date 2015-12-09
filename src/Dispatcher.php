<?php

namespace Fojuth\Stamp;

use Fojuth\Stamp\Config\Loader;
use Fojuth\Stamp\Output\Writer;
use Fojuth\Stamp\Template\Builder;
use Fojuth\Stamp\Locator\Fqn;
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
        $declaration = new Declaration($alias, $fqn);

        $config = $this->getConfig();
        $fqn = new Fqn($declaration, $config->getSources());

        $builder = $this->getBuilder($declaration, $fqn);

        $writer = new Writer($config, $fqn);
        $writer->makeClass($builder->make());

        $this->results[] = [
            'declaration' => $declaration,
            'path' => $fqn->getFilePath(),
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
//    protected function getTemplateLocator(Loader $config, Declaration $declaration)
//    {
//        $definitionFactory = new DefinitionFactory($config->getDefinitions());
//
//        $templateLocator = new TemplateLocator($config, $definitionFactory);
//        $templateLocator->setDeclaration($declaration);
//
//        return $templateLocator;
//    }

    /**
     * @param Declaration $declaration
     * @return Builder
     */
    protected function getBuilder(Declaration $declaration, Fqn $fqn)
    {
        $builder = new Builder($fqn);
        $builder->setDeclaration($declaration);

        return $builder;
    }
}
