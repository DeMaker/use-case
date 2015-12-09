<?php

namespace Fojuth\Stamp\Template;

use Fojuth\Stamp\Declaration;
use Fojuth\Stamp\Locator\Fqn;
use Memio\Model\Object;
use Memio\Model\Property;
use Memio\Model\Method;
use Memio\Model\Argument;

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

    public function __construct(Fqn $fqn)
    {
        $this->fqn = $fqn;
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
        $object = Object::make($this->declaration->getFqn())
//                    ->addProperty(new Property('createdAt'))
//                    ->addProperty(new Property('filename'))
            ->addMethod(
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
