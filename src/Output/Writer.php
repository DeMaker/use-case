<?php

namespace Fojuth\Stamp\Output;

use Fojuth\Stamp\Declaration;
use Fojuth\Stamp\Config\Loader;

/**
 * Creates the target file.
 */
class Writer
{

    /**
     * @var Loader
     */
    protected $config;

    public function __construct(Loader $config)
    {
        $this->config = $config;
    }

    public function makeClass(Declaration $declaration, $content)
    {
        $fqnArray = explode('\\', $declaration->getFqn());
        $className = array_pop($fqnArray);
        $dir = $this->getDir($fqnArray);
        $path = $this->getFilePath($dir, $className);

        if (true === file_exists($path)) {
            return;
        }

        if (false === is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($path, $content);
    }

    protected function getFilePath($dir, $className)
    {
        return $dir . '/' . $className . '.php';
    }

    protected function getDir(array $nsArray)
    {
        $ns = join('\\', $nsArray);

        $sources = $this->config->getSources();

        if (true === is_array($sources)) {
            foreach ($sources as $sourceNamespace => $dir) {
                if (strstr($ns, $sourceNamespace)) {
                    $nsToReplace = explode('\\', $sourceNamespace);

                    array_splice($nsArray, 0, count($nsToReplace), [$dir]);

                    break;
                }
            }
        }

        return join('/', $nsArray);
    }
}
