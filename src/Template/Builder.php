<?php

namespace DeSmart\DeMaker\Core\Template;

use DeSmart\DeMaker\Core\Declaration;
use DeSmart\DeMaker\Core\Locator\Fqn;
use Memio\Model\Object;
use Memio\Model\Property;
use Memio\Model\Method;
use Memio\Model\Argument;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Replaces placeholders in a template with proper values.
 */
class Builder
{

    /**
     * @var Declaration
     */
    protected $declaration;

    /**
     * @var
     */
    protected $fqn;

    /**
     * @var InputInterface
     */
    protected $input;

    public function __construct(Fqn $fqn, InputInterface $input)
    {
        $this->fqn = $fqn;
        $this->input = $input;
    }

    /**
     * @param Declaration $declaration
     * @return $this
     */
    public function setDeclaration(Declaration $declaration)
    {
        $this->declaration = $declaration;

        return $this;
    }

    public function make()
    {
        $object = Object::make($this->declaration->getFqn());

        if ($this->input->getOption('properties')) {
            $properties = explode(',', $this->input->getOption('properties'));

            foreach ($properties as $property) {
                $object->addProperty(new Property(trim($property)));
            }
        }

        $object->addMethod(
                Method::make('__construct')
                    ->addArgument(new Argument('DateTime', 'createdAt'))
                    ->addArgument(new Argument('string', 'filename'))
            );

        return $object;
    }

    /**
     * Returns class' name fetched from FQN.
     *
     * @return string
     */
    protected function getClassName()
    {
        $fqnArray = explode('\\', $this->declaration->getFqn());

        return array_pop($fqnArray);
    }
}
