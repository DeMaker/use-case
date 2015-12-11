<?php

namespace DeSmart\DeMaker\Core\Config;

/**
 * Returns PSR-4 namespace map from provided source.
 */
class Psr4
{

    /**
     * Returns PSR-4 map from composer.json
     *
     * @param string $json
     * @return array
     */
    public function getFromComposerFile($json)
    {
        $jsonArray = json_decode(str_replace('\\', '\\\\', $json), true);

        if (
            false === array_key_exists('autoload', $jsonArray)
            || false === array_key_exists('psr-4', $jsonArray['autoload'])
        ) {
            return [];
        }

        $map = [];

        foreach ($jsonArray['autoload']['psr-4'] as $namespace => $dir) {
            $map[preg_replace('/\\\$/', '', $namespace)] = $dir;
        }

        return $map;
    }
}
