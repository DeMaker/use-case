<?php namespace DeSmart\DeMaker\UseCase\Schema;

use DeSmart\DeMaker\Core\Schema\BuildStrategyInterface;
use Memio\Model\Argument;
use Memio\Model\Method;
use Memio\Model\Object;
use Memio\Model\Type;
use Symfony\Component\Console\Input\InputInterface;

class CommandHandlerBuildStrategy implements BuildStrategyInterface
{
    protected $commandFqn;
    protected $handlerFqn;
    protected $responseFqn;
    protected $responseProperties;

    /**
     * @param InputInterface $input
     */
    public function __construct(InputInterface $input)
    {
        $this->commandFqn = $input->getArgument('fqn');
        $this->handlerFqn = sprintf('%sHandler', $input->getArgument('fqn'));
        $this->responseFqn = sprintf('%sResponse', $input->getArgument('fqn'));

        if ($input->hasOption('outputProperties')) {
            $this->responseProperties = explode(',', $input->getOption('outputProperties'));
        }
    }

    /**
     * @return Object[]
     */
    public function make()
    {
        $handler = Object::make($this->handlerFqn);

        $handle = new Method('handle');
        $handle->makePublic();

        $command = new Argument($this->commandFqn, 'command');
        $handle->addArgument($command);

        if (false === empty($this->responseProperties)) {
            $responseArguments = implode(
                ', ',
                array_map(
                    function ($argument) {
                        list($argumentName, $argumentType) = explode(':', $argument);

                        return sprintf('$%s', $argumentName);
                    },
                    $this->responseProperties
                )
            );
        }

        $body = str_replace("\t", "    ", sprintf("\t\treturn new %s(%s);", $this->responseFqn, $responseArguments));

        $handle->setBody($body);

        $handler->addMethod($handle);

        return [$handler];
    }
}