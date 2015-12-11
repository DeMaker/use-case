<?php

namespace tests\Locator;

use DeSmart\DeMaker\Core\Locator\Fqn;

class FqnTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var string
     */
    protected $fqnString = 'Foo\\Bar\\Baz';

    /**
     * @test
     */
    public function it_returns_file_path()
    {
        $fqn = new Fqn($this->fqnString, []);

        $this->assertEquals('Foo/Bar/Baz.php', $fqn->getFilePath());
    }

    /**
     * @test
     */
    public function it_returns_dir()
    {
        $fqn = new Fqn($this->fqnString, []);

        $this->assertEquals('Foo/Bar', $fqn->getDir());
    }

    /**
     * @test
     */
    public function it_returns_unchanged_dir_array_if_no_sources_defined()
    {
        $fqn = new Fqn($this->fqnString, []);

        $this->assertEquals(['Foo', 'Bar'], $fqn->getDirWithNamespace());
    }

    /**
     * @test
     */
    public function it_returns_file_path_including_ns()
    {
        $fqn = new Fqn($this->fqnString, ['Foo\\Bar' => 'src']);

        $this->assertEquals('src/Baz.php', $fqn->getFilePath());
    }

    /**
     * @test
     */
    public function it_returns_dir_including_ns()
    {
        $fqn = new Fqn($this->fqnString, ['Foo\\Bar' => 'src']);

        $this->assertEquals('src', $fqn->getDir());
    }

    /**
     * @test
     */
    public function it_returns_dir_array_including_ns()
    {
        $fqn = new Fqn($this->fqnString, ['Foo\\Bar' => 'src']);

        $this->assertEquals(['src'], $fqn->getDirWithNamespace());
    }
}
