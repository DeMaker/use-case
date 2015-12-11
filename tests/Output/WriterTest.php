<?php

namespace tests\Output;

use DeSmart\DeMaker\Core\Config\Loader;
use DeSmart\DeMaker\Core\Locator\Fqn;
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

        $fqn = $this->prophesize(Fqn::class);
        $fqn->getFilePath()->willReturn($path = 'Foo/Bar/Baz.php');
        $fqn->getDir()->willReturn('Foo/Bar');

        $writer = new Writer($printer->reveal());
        $writer->makeClass($object->reveal(), $fqn->reveal());

        $this->assertTrue(file_exists($path));
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

        $fqn = $this->prophesize(Fqn::class);
        $fqn->getFilePath()->willReturn($path = 'Somedir/Foo.php');
        $fqn->getDir()->willReturn('Somedir');

        $writer = new Writer($printer->reveal());
        $writer->makeClass($object->reveal(), $fqn->reveal());

        $this->assertTrue(file_exists($path));
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

        $fqn = $this->prophesize(Fqn::class);
        $fqn->getFilePath()->willReturn($path = 'wat/SomeDir/Baz.php');
        $fqn->getDir()->willReturn('wat/SomeDir');

        $writer = new Writer($printer->reveal());
        $writer->makeClass($object->reveal(), $fqn->reveal());

        $this->assertTrue(file_exists($path));
    }
}
