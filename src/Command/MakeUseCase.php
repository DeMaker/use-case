<?php namespace DeSmart\DeMaker\UseCase\Command;

use DeSmart\DeMaker\Core\Dispatcher\Dispatcher;
use DeSmart\DeMaker\UseCase\Schema\DTOBuildStrategy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class MakeUseCase extends Command
{
    protected function configure()
    {
        $this->setName('make')
             ->addArgument('fqn', InputArgument::REQUIRED, 'FQN of the target class')
             ->addOption('inputProperties', 'i', InputOption::VALUE_OPTIONAL, 'Properties passed to command (comma separated)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $results = (new Dispatcher(new DTOBuildStrategy($input)))->run();

//        foreach ($results as $result) {
//            $declaration = $result['declaration'];
//
//            $output->writeln("Generated {$declaration->getAlias()} ({$declaration->getFqn()}) at {$result['path']}");
//        }
    }
}