<?php

namespace Fojuth\Stamp\Template;

use Fojuth\Stamp\Declaration;
use Memio\Memio\Config\Build;
use Memio\Model\File;
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
//        $file = File::make('src/Vendor/Project/MyService.php')
//            ->setStructure(
                $object = Object::make($this->getClassNamespace())
//                    ->addProperty(new Property('createdAt'))
//                    ->addProperty(new Property('filename'))
                    ->addMethod(
                        Method::make('__construct')
                            ->addArgument(new Argument('DateTime', 'createdAt'))
                            ->addArgument(new Argument('string', 'filename'))
                    );
//            )
//        ;
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

    /**
     * Returns class' namespace fetched from FQN.
     *
     * @return string
     */
    protected function getClassNamespace()
    {
        $fqnArray = explode('\\', $this->declaration->getFqn());

        array_pop($fqnArray);

        return join('\\', $fqnArray);
    }
}
