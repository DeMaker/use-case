<?php

namespace DeSmart\DeMaker\Core\Config;

use DeSmart\DeMaker\Core\Config\Exception\InvalidConfigException;
use DeSmart\DeMaker\Core\Config\Exception\ConfigIncompleteException;

class Loader
{

    /**
     * @var string
     */
    protected $sources;

    /**
     * @var string
     */
    protected $testsDir;

    /**
     * @var array
     */
    protected $definitions;

    public function __construct($configJson)
    {
        $config = json_decode($configJson, true);

        if (false === is_array($config)) {
            throw new InvalidConfigException;
        }

        $this->validateBasicKeys($config);
        $this->storeConfig($config);
    }

    protected function validateBasicKeys(array $config)
    {
        $basicKeys = ['sources', 'definitions'];

        foreach ($basicKeys as $key) {
            if (false === array_key_exists($key, $config)) {
                throw new ConfigIncompleteException;
            }
        }
    }

    protected function storeConfig(array $config)
    {
        $this->sources = $config['sources'];
        $this->testsDir = $config['test-dir'];
        $this->definitions = $config['definitions'];
    }

    public function getSources()
    {
        return $this->sources;
    }

    public function getTestsDir()
    {
        return $this->testsDir;
    }

    public function getDefinitions()
    {
        return $this->definitions;
    }
}
