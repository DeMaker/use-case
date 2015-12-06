<?php

namespace Fojuth\Stamp\Config;

use Fojuth\Stamp\Config\Exception\InvalidConfigException;
use Fojuth\Stamp\Config\Exception\ConfigIncompleteException;

class Loader
{

    /**
     * @var string
     */
    protected $templateDir;

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
        $basicKeys = ['template-dir', 'sources', 'definitions'];

        foreach ($basicKeys as $key) {
            if (false === array_key_exists($key, $config)) {
                throw new ConfigIncompleteException;
            }
        }
    }

    protected function storeConfig(array $config)
    {
        $this->templateDir = $config['template-dir'];
        $this->sources = $config['sources'];
        $this->testsDir = $config['test-dir'];
        $this->definitions = $config['definitions'];
    }

    public function getTemplateDir()
    {
        return $this->templateDir;
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
