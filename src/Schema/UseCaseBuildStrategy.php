<?php namespace DeSmart\DeMaker\UseCase\Schema;

use DeSmart\DeMaker\Core\Schema\BuildStrategyInterface;
use Symfony\Component\Console\Input\InputInterface;

class UseCaseBuildStrategy implements BuildStrategyInterface
{
    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @param InputInterface $input
     */
    public function __construct(InputInterface $input)
    {
        $this->input = $input;
    }

    /**
     * @return Object[]
     */
    public function make()
    {
        return array_merge(
            (new CommandBuildStrategy($this->input))->make(),
            (new CommandValidatorBuildStrategy($this->input))->make(),
            (new CommandHandlerBuildStrategy($this->input))->make(),
            (new CommandResponseBuildStrategy($this->input))->make()
        );
    }
}