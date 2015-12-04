<?php

namespace Fojuth\Stamp\Config;

use Fojuth\Stamp\Config\Exception\InvalidConfigException;
use Fojuth\Stamp\Config\Exception\ConfigIncompleteException;

class Loader
{
    public function __construct($configJson)
    {
        $config = json_decode($configJson, true);

        if (false === is_array($config)) {
            throw new InvalidConfigException;
        }

        $this->validateBasicKeys($config);
    }

    protected function validateBasicKeys(array $config)
    {
        $basicKeys = ['template-dir', 'source-dir', 'definitions'];

        foreach ($basicKeys as $key) {
            if (false === array_key_exists($key, $config)) {
                throw new ConfigIncompleteException;
            }
        }
    }
}
