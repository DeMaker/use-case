<?php

namespace tests\Output;

use DeSmart\DeMaker\Core\Config\Loader;
use DeSmart\DeMaker\Core\Output\Writer;
use Memio\Model\Object;
use Memio\PrettyPrinter\PrettyPrinter;
use Prophecy\Argument;

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
        $printer = $this->prophesize(PrettyPrinter::class);
        $printer->generateCode(Argument::any())->willReturn($content = 'foo bar baz');

        $object = $this->prophesize(Object::class);
        $object->getFullyQualifiedName()->willReturn('Foo\Bar\Baz');

        $writer = new Writer($printer->reveal());
        $writer->makeClass($object->reveal(), []);

        $this->assertTrue(file_exists($path = 'Foo/Bar/Baz.php'));
        $this->assertEquals($content, file_get_contents($path));
    }

    /**
     * @test
     */
    public function it_does_nothing_if_file_exists()
    {
        $printer = $this->prophesize(PrettyPrinter::class);

        $object = $this->prophesize(Object::class);
        $object->getFullyQualifiedName()->willReturn('Somedir\Foo');

        $writer = new Writer($printer->reveal());
        $writer->makeClass($object->reveal(), []);

        $this->assertTrue(file_exists($path = 'Somedir/Foo.php'));
        $this->assertEquals('123', file_get_contents($path));
    }

    /**
     * @test
     */
    public function it_sets_proper_path_from_config()
    {
        $printer = $this->prophesize(PrettyPrinter::class);

        $object = $this->prophesize(Object::class);
        $object->getFullyQualifiedName()->willReturn('Foo\Bar\SomeDir\Baz');

        $writer = new Writer($printer->reveal());
        $writer->makeClass($object->reveal(), [
            'Something' => 'nope',
            'Foo\Bar' => 'wat',
            'Not\Important' => 'hello',
        ]);

        $this->assertTrue(file_exists('wat/SomeDir/Baz.php'));
    }
}
