<?php

namespace tests;

use Fojuth\Stamp\Declaration;
use Fojuth\Stamp\Template\Builder;

class BuilderTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_substitutes_values()
    {
        $templateContent = <<<EOT
<?php

namespace {{NAMESPACE}};

class {{CLASSNAME}} {
}
EOT;

        $expectedContent = <<<EOT
<?php

namespace Foo\Bar;

class Baz {
}
EOT;

        $declaration = new Declaration('baz', 'Foo\Bar\Baz');

        $builder = new Builder;
        $builder->setTemplateContent($templateContent);
        $builder->setDeclaration($declaration);

        $this->assertEquals($expectedContent, $builder->make());
    }
}
