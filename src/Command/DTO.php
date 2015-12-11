<?php

namespace DeSmart\DeMaker\Core\Command;

use DeSmart\DeMaker\Core\Dispatcher\Dispatcher;
use DeSmart\DeMaker\Core\Schema\DTOBuildStrategy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class DTO extends Command
{
    protected function configure()
    {
        $this->setName('dto')
            ->addArgument('fqn', InputArgument::REQUIRED, 'FQN of the class to be generated')
            ->addOption('inputProperties', 'p', InputOption::VALUE_REQUIRED, 'Properties to generate (comma separated)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $buildStrategy = new DTOBuildStrategy($input);

        $dispatcherResponses = (new Dispatcher($buildStrategy))->run();

        foreach ($dispatcherResponses as $response) {
            $output->writeln("Generated DTO {$response->getFqn()} at {$response->getPath()}");
        }
    }
}
