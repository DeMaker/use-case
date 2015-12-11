<?php

namespace DeSmart\DeMaker\Core\Config;

class Psr4
{
    public function getFromComposerFile($json)
    {
        var_dump($json, json_decode($json));
        die;
        $jsonArray = json_decode($json, true);

        var_dump($jsonArray);
        die;
    }
}
