<?php

namespace DeSmart\DeMaker\Core\Dispatcher;

use DeSmart\DeMaker\Core\Config\Psr4;
use DeSmart\DeMaker\Core\Output\Writer;
use DeSmart\DeMaker\Core\Template\Builder;
use DeSmart\DeMaker\Core\Locator\Fqn;
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

        $fqn = new Fqn($declaration, $this->getSources());

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
     * @param Declaration $declaration
     * @return Builder
     */
    protected function getBuilder(Declaration $declaration, Fqn $fqn)
    {
        $builder = new Builder($fqn, $this->input);
        $builder->setDeclaration($declaration);

        return $builder;
    }

    protected function getSources()
    {
        $loader = new Psr4;

        return $loader->getFromComposerFile(file_get_contents('composer.json'));
    }
}
