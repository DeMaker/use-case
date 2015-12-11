<?php

namespace DeSmart\DeMaker\Core\Dispatcher;

class DispatcherResponse
{

    /**
     * @var string
     */
    private $fqn;

    /**
     * @var string
     */
    private $path;

    public function __construct($fqn, $path)
    {

        $this->fqn = $fqn;
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getFqn()
    {
        return $this->fqn;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
