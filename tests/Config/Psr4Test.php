<?php

namespace tests\Config;

use DeSmart\DeMaker\Core\Config\Psr4;

class Psr4Test extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_returns_autoload_namespaces()
    {
        $json = <<<EOT
{
    "autoload": {
        "psr-4": {
            "DeSmart\\DeMaker\\Core\\": "src/",
            "Foo\\Bar\\": "tmp/foo/bar"
        }
    }
}
EOT;

        $loader = new Psr4();

        $result = $loader->getFromComposerFile($json);

        $this->assertEquals([
            'DeSmart\\DeMaker\\Core' => 'src',
            'Foo\\Bar' => 'tmp/foo/bar',
        ], $result);
    }
}
