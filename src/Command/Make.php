<?php

namespace Fojuth\Stamp\Command;

use Fojuth\Stamp\Dispatcher;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Make extends Command
{
    protected function configure()
    {
        $this->setName('make')
            ->addArgument('alias', InputArgument::REQUIRED, 'What to generate')
            ->addArgument('fqn', InputArgument::REQUIRED, 'FQN of the target class');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $alias = $input->getArgument('alias');
        $fqn = $input->getArgument('fqn');

        $results = (new Dispatcher)->run($alias, $fqn);

        foreach ($results as $result) {
            $declaration = $result['declaration'];

            $output->writeln("Generated {$declaration->getAlias()} ({$declaration->getFqn()}) at {$result['path']} ");
        }
    }
}
