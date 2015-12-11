<?php

namespace DeSmart\DeMaker\Core\Output;

use DeSmart\DeMaker\Core\Config\Loader;
use DeSmart\DeMaker\Core\Locator\Fqn;
use Memio\Model\File;
use Memio\Model\Object as Scheme;
use Memio\PrettyPrinter\PrettyPrinter;

/**
 * Creates the target file.
 */
class Writer
{

    /**
     * @var PrettyPrinter
     */
    protected $printer;

    public function __construct(PrettyPrinter $printer)
    {
        $this->printer = $printer;
    }

    /**
     * Create the file.
     *
     * @param Scheme $scheme
     * @param Fqn $fqn
     */
    public function makeClass(Scheme $scheme, Fqn $fqn)
    {
        $path = $fqn->getFilePath();

        // File already exists - do nothing
        if (true === file_exists($path)) {
            return;
        }

        $dir = $fqn->getDir();

        // Create target dir, if it doesn't exist
        if (false === is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($path, $this->generate($scheme, $path));
    }

    /**
     * @param Scheme $scheme
     * @param $path
     * @return string
     */
    protected function generate(Scheme $scheme, $path)
    {
        $file = File::make($path)
            ->setStructure($scheme);

        return $this->printer->generateCode($file);
    }
}
