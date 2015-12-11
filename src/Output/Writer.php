<?php

namespace DeSmart\DeMaker\Core\Output;

use DeSmart\DeMaker\Core\Config\Loader;
use DeSmart\DeMaker\Core\Locator\Fqn;
use Memio\Memio\Config\Build;
use Memio\Model\File;
use Memio\Model\Object as Scheme;

/**
 * Creates the target file.
 */
class Writer
{

    /**
     * @var Loader
     */
    protected $config;

    /**
     * @var string
     */
    protected $compiledFilePath;

    /**
     * @var Fqn
     */
    protected $fqn;

    public function __construct(Loader $config, Fqn $fqn)
    {
        $this->config = $config;
        $this->fqn = $fqn;
    }

    /**
     * Create the file.
     *
     * @param Scheme $scheme
     */
    public function makeClass(Scheme $scheme)
    {
        $path = $this->fqn->getFilePath();

        // File already exists - do nothing
        if (true === file_exists($path)) {
            return;
        }

        $dir = $this->fqn->getDir();

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

        $generator = Build::prettyPrinter();

        return $generator->generateCode($file);
    }
}
