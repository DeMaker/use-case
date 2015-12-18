<?php namespace DeSmart\DeMaker\UseCase\Schema;

use DeSmart\DeMaker\Core\Schema\BuildStrategyInterface;
use Memio\Model\Argument;
use Memio\Model\Method;
use Memio\Model\Object;
use Symfony\Component\Console\Input\InputInterface;

class CommandValidatorBuildStrategy implements  BuildStrategyInterface
{
    /**
     * @var string
     */
    protected $validatorFqn;

    /**
     * @var string
     */
    protected $commandFqn;

    /**
     * @param InputInterface $input
     */
    public function __construct(InputInterface $input)
    {
        $this->commandFqn = $input->getArgument('fqn');
        $this->validatorFqn = sprintf('%sValidator', $input->getArgument('fqn'));
    }

    /**
     * @return Object[]
     */
    public function make()
    {
        $validator = Object::make($this->validatorFqn);

        $validate = new Method('validate');
        $validate->makePublic();

        $command = new Argument($this->commandFqn, 'command');
        $validate->addArgument($command);

        $validator->addMethod($validate);

        return [$validator];
    }
}