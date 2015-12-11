<?php

namespace DeSmart\DeMaker\Core\Template;

class Definition
{

    /**
     * @var string
     */
    protected $alias;

    /**
     * @var string
     */
    protected $testTemplateFile;

    /**
     * @var array
     */
    protected $cascade;

    public function __construct(array $definitionArray)
    {
        $this->alias = $definitionArray['alias'];

        if (true === array_key_exists('cascade', $definitionArray)) {
            $this->cascade = $definitionArray['cascade'];
        }

        if (true === array_key_exists('test-template', $definitionArray)) {
            $this->testTemplateFile = $definitionArray['test-template'];
        }
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
    public function getTestTemplateFile()
    {
        return $this->testTemplateFile;
    }

    /**
     * @return array
     */
    public function getCascade()
    {
        return $this->cascade;
    }
}
