<?php namespace DeSmart\DeMaker\UseCase\Schema;

use DeSmart\DeMaker\Core\Schema\DTOBuildStrategy;
use Symfony\Component\Console\Input\InputInterface;

class CommandResponseBuildStrategy extends DTOBuildStrategy
{
    /**
     * @param InputInterface $input
     */
    public function __construct(InputInterface $input)
    {
        $this->fqn = sprintf('%sResponse', $input->getArgument('fqn'));

        if ($input->hasOption('outputProperties')) {
            $this->properties = explode(',', $input->getOption('outputProperties'));
        }
    }
}