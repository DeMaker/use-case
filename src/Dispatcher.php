<?php

namespace Fojuth\Stamp;

use Fojuth\Stamp\Config\Loader;
use Fojuth\Stamp\Output\Writer;
use Fojuth\Stamp\Template\Builder;
use Fojuth\Stamp\Locator\Fqn;
use Fojuth\Stamp\Template\DefinitionFactory;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Dispatcher responsible for running Stamp.
 */
class Dispatcher
{

    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var array
     */
    protected $results = [];

    public function __construct(InputInterface $input)
    {
        $this->input = $input;
    }

    public function run()
    {
        $declaration = new Declaration(
            $this->input->getArgument('alias'),
            $this->input->getArgument('fqn')
        );

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
        $builder = new Builder($fqn, $this->input);
        $builder->setDeclaration($declaration);

        return $builder;
    }
}
