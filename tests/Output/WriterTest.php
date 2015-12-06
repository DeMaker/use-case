<?php

namespace tests\Output;

use Fojuth\Stamp\Config\Loader;
use Fojuth\Stamp\Declaration;
use Fojuth\Stamp\Output\Writer;

class WriterTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        mkdir('Somedir');
        file_put_contents('Somedir/Foo.php', '123');
    }

    public function tearDown()
    {
        unlink('Somedir/Foo.php');
        rmdir('Somedir');

        @ unlink('wat/SomeDir/Baz.php');
        @ rmdir('wat/SomeDir');
        @ rmdir('wat');

        @ unlink('Foo/Bar/Baz.php');
        @ rmdir('Foo/Bar');
        @ rmdir('Foo');
    }

    /**
     * @test
     */
    public function it_creates_the_final_class()
    {
        $declaration = new Declaration('baz', 'Foo\Bar\Baz');
        $content = 'foo bar baz';

        $config = $this->prophesize(Loader::class);

        $writer = new Writer($config->reveal());
        $writer->makeClass($declaration, $content);

        $this->assertTrue(file_exists($path = 'Foo/Bar/Baz.php'));
        $this->assertEquals($content, file_get_contents($path));
    }

    /**
     * @test
     */
    public function it_does_nothing_if_file_exists()
    {
        $declaration = new Declaration('baz', 'Somedir\Foo');
        $content = 'foo bar baz';

        $config = $this->prophesize(Loader::class);

        $writer = new Writer($config->reveal());
        $writer->makeClass($declaration, $content);

        $this->assertTrue(file_exists($path = 'Somedir/Foo.php'));
        $this->assertEquals('123', file_get_contents($path));
    }

    /**
     * @test
     */
    public function it_sets_proper_path_from_config()
    {
        $declaration = new Declaration('baz', 'Foo\Bar\SomeDir\Baz');

        $config = $this->prophesize(Loader::class);
        $config->getSources()->willReturn([
            'Something' => 'nope',
            'Foo\Bar' => 'wat',
            'Not\Important' => 'hello',
        ]);

        $writer = new Writer($config->reveal());
        $writer->makeClass($declaration, '');

        $this->assertTrue(file_exists('wat/SomeDir/Baz.php'));
    }
}
