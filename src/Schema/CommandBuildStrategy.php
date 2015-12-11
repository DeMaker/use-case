<?php namespace DeSmart\DeMaker\UseCase\Schema;

use DeSmart\DeMaker\Core\Schema\DTOBuildStrategy;
use Symfony\Component\Console\Input\InputInterface;

class CommandBuildStrategy extends DTOBuildStrategy
{
    /**
     * @param InputInterface $input
     */
    public function __construct(InputInterface $input)
    {
        $this->fqn = $input->getArgument('fqn');

        if ($input->hasOption('inputProperties')) {
            $this->properties = explode(',', $input->getOption('inputProperties'));
        }
    }
}