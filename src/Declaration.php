<?php

namespace Fojuth\Stamp;

class Declaration
{

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var string
     */
    protected $fqn;

    /**
     * @var bool
     */
    protected $cascade = true;

    /**
     * @var bool
     */
    protected $generateTests = true;

    public function __construct($alias, $fqn)
    {
        $this->alias = $alias;
        $this->fqn = $fqn;
    }

    /**
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @return string
     */
    public function getFqn()
    {
        return $this->fqn;
    }

    /**
     * @return boolean
     */
    public function isCascade()
    {
        return $this->cascade;
    }

    /**
     * @param boolean $cascade
     * @return $this
     */
    public function setCascade($cascade)
    {
        $this->cascade = (bool)$cascade;

        return $this;
    }

    /**
     * @return boolean
     */
    public function generateTests()
    {
        return $this->generateTests;
    }

    /**
     * @param boolean $generateTests
     * @return $this
     */
    public function setGenerateTests($generateTests)
    {
        $this->generateTests = (bool)$generateTests;

        return $this;
    }
}
