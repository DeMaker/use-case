<?php

namespace DeSmart\DeMaker\Core\Command;

use DeSmart\DeMaker\Core\Dispatcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Make extends Command
{
    protected function configure()
    {
        $this->setName('make')
            ->addArgument('alias', InputArgument::REQUIRED, 'What to generate')
            ->addArgument('fqn', InputArgument::REQUIRED, 'FQN of the target class')
            ->addOption('properties', 'p', InputOption::VALUE_REQUIRED, 'Properties to generate (comma separated)')
            ->addOption('getters', 'g', InputOption::VALUE_NONE, 'Generate getters')
            ->addOption('setters', 's', InputOption::VALUE_NONE, 'Generate setters');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $results = (new Dispatcher($input))->run();

        foreach ($results as $result) {
            $declaration = $result['declaration'];

            $output->writeln("Generated {$declaration->getAlias()} ({$declaration->getFqn()}) at {$result['path']}");
        }
    }
}
