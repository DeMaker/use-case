<?php

namespace DeSmart\DeMaker\Core\Schema;

use Memio\Model\Argument;
use Memio\Model\Method;
use Memio\Model\Object;
use Memio\Model\Phpdoc\MethodPhpdoc;
use Memio\Model\Phpdoc\ParameterTag;
use Memio\Model\Phpdoc\PropertyPhpdoc;
use Memio\Model\Phpdoc\ReturnTag;
use Memio\Model\Phpdoc\VariableTag;
use Memio\Model\Property;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Build strategy responsible for creating DTO classes.
 */
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
        $construct->setPhpdoc(MethodPhpdoc::make());

        $dto->addMethod($construct);

        $constructBodyElements = $this->handleMethodProperties($construct, $dto);

        $construct->setBody(implode("\n", $constructBodyElements));

        return [$dto];
    }

    /**
     * @param $property
     * @return array
     */
    protected function getPropertyDefinition($property)
    {
        return explode(':', $property);
    }

    /**
     * @param Method $construct
     * @param Object $dto
     * @return array
     */
    protected function handleMethodProperties(Method $construct, Object $dto)
    {
        $constructBodyElements = [];

        foreach($this->properties as $property) {
            list($propertyName, $propertyType) = $this->getPropertyDefinition($property);

            $newProperty = new Property($propertyName);
            $newProperty->makePrivate();
            $newProperty->setPhpdoc(PropertyPhpdoc::make()
                ->setVariableTag(new VariableTag($propertyType))
            );

            $dto->addProperty($newProperty);

            $argument = new Argument($propertyType, $propertyName);
            $construct->addArgument($argument);
            $construct->getPhpdoc()->addParameterTag(new ParameterTag($propertyType, $propertyName));

            $constructBodyElements[] = sprintf("        \$this->%s = $%s;", $propertyName, $propertyName);

            $newMethod = new Method(sprintf('get%s', ucfirst($propertyName)));
            $newMethod->makePublic();
            $newMethod->setPhpdoc(MethodPhpdoc::make()
                ->setReturnTag(new ReturnTag($propertyType))
            );

            $body = str_replace("\t", '    ', sprintf("\t\treturn \$this->%s;", $propertyName));
            $newMethod->setBody($body);

            $dto->addMethod($newMethod);
        }

        return $constructBodyElements;
    }
}