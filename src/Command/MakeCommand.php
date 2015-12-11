<?php

namespace DeSmart\DeMaker\UseCase\Command;

use DeSmart\DeMaker\Core\Dispatcher\Dispatcher;
use DeSmart\DeMaker\UseCase\Schema\CommandBuildStrategy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCommand extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('command')
             ->addArgument('fqn', InputArgument::REQUIRED, 'FQN of the target class')
             ->addOption('inputProperties', 'i', InputOption::VALUE_OPTIONAL, 'Properties passed to command (comma separated)');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dispatcherResponses = (new Dispatcher(new CommandBuildStrategy($input)))->run();

        foreach ($dispatcherResponses as $response) {
            $output->writeln("Generated command {$response->getFqn()} at {$response->getPath()}");
        }
    }
}