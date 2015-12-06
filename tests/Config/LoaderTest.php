<?php

namespace tests;

use Fojuth\Stamp\Config\Loader;
use Fojuth\Stamp\Config\Exception\InvalidConfigException;
use Fojuth\Stamp\Config\Exception\ConfigIncompleteException;

class LoaderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_throws_exception_if_config_invalid()
    {
        $content = 'foo bar';

        $this->setExpectedException(InvalidConfigException::class);

        new Loader($content);
    }


    /**
     * @test
     * @dataProvider require_config_value_provider
     */
    public function it_throws_exception_if_required_values_are_missing($config)
    {
        $this->setExpectedException(ConfigIncompleteException::class);

        new Loader(json_encode($config));
    }

    public function require_config_value_provider()
    {
        return [
            [
                []
            ],
            [
                ['template-dir']
            ],
            [
                ['template-dir', 'sources']
            ],
        ];
    }

    /**
     * @test
     */
    public function it_stores_config_values()
    {
        $config = [
            'template-dir' => 'stamp-tpl',
            'sources' => 'src',
            'test-dir' => 'tests',
            'definitions' => [],
        ];

        $loader = new Loader(json_encode($config));

        $this->assertEquals($config['template-dir'], $loader->getTemplateDir());
        $this->assertEquals($config['sources'], $loader->getSources());
        $this->assertEquals($config['test-dir'], $loader->getTestsDir());
        $this->assertEquals($config['definitions'], $loader->getDefinitions());
    }
}
