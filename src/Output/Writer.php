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

    /**
     * @var string
     */
    protected $compiledFilePath;

    public function __construct(Loader $config)
    {
        $this->config = $config;
    }

    /**
     * Create the file.
     *
     * @param Declaration $declaration
     * @param string $content
     */
    public function makeClass(Declaration $declaration, $content)
    {
        $fqnArray = explode('\\', $declaration->getFqn());
        $className = array_pop($fqnArray);
        $dir = $this->getDir($fqnArray);
        $path = $this->getFilePath($dir, $className);

        // File already exists - do nothing
        if (true === file_exists($path)) {
            return;
        }

        // Create target dir, if it doesn't exist
        if (false === is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        file_put_contents($path, $content);
    }

    /**
     * Returns file's path.
     *
     * @param string $dir
     * @param string $className
     * @return string
     */
    protected function getFilePath($dir, $className)
    {
        $this->compiledFilePath = $dir . '/' . $className . '.php';

        return $this->compiledFilePath;
    }

    /**
     * @return string
     */
    public function getCompiledFilePath()
    {
        return $this->compiledFilePath;
    }

    /**
     * Returns dir path from namespace.
     *
     * @param array $nsArray
     * @return string
     */
    protected function getDir(array $nsArray)
    {
        $ns = join('\\', $nsArray);

        $sources = $this->config->getSources();

        if (true === is_array($sources)) {
            $nsArray = $this->getDirWithNamespace($sources, $ns);
        }

        return join('/', $nsArray);
    }

    /**
     * Swap part of dir path based on PSR-4 namespaces.
     *
     * @param array $sources
     * @param string $ns
     * @return array
     */
    protected function getDirWithNamespace(array $sources, $ns)
    {
        $nsArray = explode('\\', $ns);

        foreach ($sources as $sourceNamespace => $dir) {
            if (strstr($ns, $sourceNamespace)) {
                $nsToReplace = explode('\\', $sourceNamespace);

                array_splice($nsArray, 0, count($nsToReplace), [$dir]);

                break;
            }
        }

        return $nsArray;
    }
}
