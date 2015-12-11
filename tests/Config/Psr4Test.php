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
                        "DeSmart\\DeMaker\\Core\\": "src",
                        "Foo\\Bar\\": "tmp/foo/bar"
                    }
                }
            }
EOT;

        $loader = new Psr4();

        $this->assertEquals([
            'DeSmart\\DeMaker\\Core' => 'src',
            'Foo\\Bar' => 'tmp/foo/bar',
        ], $loader->getFromComposerFile($json));
    }

    /**
     * @test
     */
    public function it_returns_empty_array_if_no_psr4()
    {
        $json = <<<EOT
            {
                "autoload": {
                    "foo": "bar"
                }
            }
EOT;

        $loader = new Psr4();

        $this->assertEquals([], $loader->getFromComposerFile($json));
    }

    /**
     * @test
     */
    public function it_returns_empty_array_if_no_autoload()
    {
        $json = <<<EOT
            {
                "foo": "bar"
            }
EOT;

        $loader = new Psr4();

        $this->assertEquals([], $loader->getFromComposerFile($json));
    }
}
