<?php

namespace Fojuth\Stamp\Template;

use Fojuth\Stamp\Declaration;

/**
 * Replaces placeholders in a template with proper values.
 */
class Builder
{

    /**
     * @var string
     */
    protected $template;

    /**
     * @var Declaration
     */
    protected $declaration;

    /**
     * @param $template
     * @return $this
     */
    public function setTemplateContent($template)
    {
        $this->template = $template;

        return $this;
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
        return preg_replace([
            '/{{NAMESPACE}}/',
            '/{{CLASSNAME}}/',
        ], [
            $this->getClassNamespace(),
            $this->getClassName(),
        ], $this->template);
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
