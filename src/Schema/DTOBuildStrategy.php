<?php

namespace DeSmart\DeMaker\Core\Schema;

use Memio\Model\Argument;
use Memio\Model\Method;
use Memio\Model\Object;
use Memio\Model\Property;
use Symfony\Component\Console\Input\InputInterface;

class DTOBuildStrategy implements BuildStrategyInterface
{

    /**
     * @var string
     */
    protected $fqn;

    /**
     * @var array
     */
    protected $properties = [];

    /**
     * @param InputInterface $input
     */
    public function __construct(InputInterface $input)
    {
        $this->fqn = $input->getArgument('fqn');
        $properties = $input->getOption('inputProperties');

        if (false === is_null($properties)) {
            $this->properties = explode(',', $input->getOption('inputProperties'));
        }
    }

    /**
     * @return Object[]
     */
    public function make()
    {
        $dto = Object::make($this->fqn);

        $construct = new Method('__construct');
        $construct->makePublic();

        $dto->addMethod($construct);

        $constructBodyElements = [];

        foreach($this->properties as $property) {
            list($propertyName, $propertyType) = $this->getPropertyDefinition($property);

            $newProperty = new Property($propertyName);
            $newProperty->makePrivate();

            $dto->addProperty($newProperty);

            $argument = new Argument($propertyType, $propertyName);
            $construct->addArgument($argument);

            $constructBodyElements[] = sprintf("        \$this->%s = $%s;", $propertyName, $propertyName);

            $newMethod = new Method(sprintf('get%s', ucfirst($propertyName)));
            $newMethod->makePublic();

            $body = str_replace("\t", '    ', sprintf("\t\treturn \$this->%s;", $propertyName));

            $newMethod->setBody($body);

            $dto->addMethod($newMethod);
        }

        $construct->setBody(implode("\n", $constructBodyElements));

        return [$dto];
    }

    /**
     * @param $property
     * @return array
     */
    private function getPropertyDefinition($property)
    {
        return explode(':', $property);
    }
}