<?php

namespace tests\Locator;

use Fojuth\Stamp\Declaration;
use Fojuth\Stamp\Locator\Fqn;

class FqnTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_returns_file_path()
    {
        $declaration = $this->get_declaration_prophecy();

        $fqn = new Fqn($declaration->reveal(), []);

        $this->assertEquals('Foo/Bar/Baz.php', $fqn->getFilePath());
    }

    /**
     * @test
     */
    public function it_returns_dir()
    {
        $declaration = $this->get_declaration_prophecy();

        $fqn = new Fqn($declaration->reveal(), []);

        $this->assertEquals('Foo/Bar', $fqn->getDir());
    }

    protected function get_declaration_prophecy()
    {
        $declaration = $this->prophesize(Declaration::class);
        $declaration->getFqn()->willReturn('Foo\\Bar\\Baz');

        return $declaration;
    }

    /**
     * @test
     */
    public function it_returns_unchanged_dir_array_if_no_sources_defined()
    {
        $declaration = $this->get_declaration_prophecy();

        $fqn = new Fqn($declaration->reveal(), []);

        $this->assertEquals(['Foo', 'Bar'], $fqn->getDirWithNamespace());
    }

    /**
     * @test
     */
    public function it_returns_file_path_including_ns()
    {
        $declaration = $this->get_declaration_prophecy();

        $fqn = new Fqn($declaration->reveal(), ['Foo\\Bar' => 'src']);

        $this->assertEquals('src/Baz.php', $fqn->getFilePath());
    }

    /**
     * @test
     */
    public function it_returns_dir_including_ns()
    {
        $declaration = $this->get_declaration_prophecy();

        $fqn = new Fqn($declaration->reveal(), ['Foo\\Bar' => 'src']);

        $this->assertEquals('src', $fqn->getDir());
    }

    /**
     * @test
     */
    public function it_returns_dir_array_including_ns()
    {
        $declaration = $this->get_declaration_prophecy();

        $fqn = new Fqn($declaration->reveal(), ['Foo\\Bar' => 'src']);

        $this->assertEquals(['src'], $fqn->getDirWithNamespace());
    }
}
