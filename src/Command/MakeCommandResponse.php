<?php

namespace DeSmart\DeMaker\UseCase\Command;

use DeSmart\DeMaker\Core\Dispatcher\Dispatcher;
use DeSmart\DeMaker\UseCase\Schema\CommandResponseBuildStrategy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCommandResponse extends Command
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName('command-response')
             ->addArgument('fqn', InputArgument::REQUIRED, 'FQN of the target class')
             ->addOption('outputProperties', 'o', InputOption::VALUE_OPTIONAL, 'Properties passed to command response (comma separated)');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $dispatcherResponses = (new Dispatcher(new CommandResponseBuildStrategy($input)))->run();

        foreach ($dispatcherResponses as $response) {
            $output->writeln("Generated command response {$response->getFqn()} at {$response->getPath()}");
        }
    }
}