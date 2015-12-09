<?php

namespace Fojuth\Stamp\Locator;

use Fojuth\Stamp\Declaration;

class Fqn
{

    /**
     * @var Declaration
     */
    protected $declaration;

    /**
     * @var array
     */
    protected $source;

    /**
     * @var array
     */
    protected $fqnArray;

    /**
     * @var string
     */
    protected $className;

    public function __construct(Declaration $declaration, array $source)
    {
        $this->declaration = $declaration;
        $this->source = $source;
        $fqnArray = explode('\\', $declaration->getFqn());
        $this->className = array_pop($fqnArray);
        $this->fqnArray = $fqnArray;
    }

    /**
     * Returns file's path.
     *
     * @return string
     */
    public function getFilePath()
    {
        $dir = $this->getDir();

        return $dir . '/' . $this->className . '.php';
    }

    /**
     * Returns dir path from namespace.
     *
     * @return string
     */
    public function getDir()
    {
        $ns = join('\\', $this->fqnArray);

        if (true === is_array($this->source)) {
            $fqnArray = $this->getDirWithNamespace($ns);
        }

        return join('/', $fqnArray);
    }

    /**
     * Swap part of dir path based on PSR-4 namespaces.
     *
     * @return array
     */
    public function getDirWithNamespace()
    {
        $nsArray = $this->fqnArray;
        $ns = join('\\', $nsArray);

        foreach ($this->source as $sourceNamespace => $dir) {
            if (strstr($ns, $sourceNamespace)) {
                $nsToReplace = explode('\\', $sourceNamespace);

                array_splice($nsArray, 0, count($nsToReplace), [$dir]);

                break;
            }
        }

        return $nsArray;
    }
}
