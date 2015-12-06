<?php

namespace Fojuth\Stamp\Output;

use Fojuth\Stamp\Declaration;

/**
 * Creates the target file.
 */
class Writer
{
    public function makeClass(Declaration $declaration, $content)
    {
        $fqnArray = explode('\\', $declaration->getFqn());
        $className = array_pop($fqnArray);
        $dir = join('/', $fqnArray);
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
}
