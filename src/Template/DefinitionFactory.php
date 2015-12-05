<?php

namespace Fojuth\Stamp\Template;

use Fojuth\Stamp\Template\Exception\DefinitionNotFoundException;

/**
 * Definition factory for hydrating definitions.
 */
class DefinitionFactory
{

    /**
     * @var array
     */
    protected $definitions;

    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    /**
     * Returns an instance of a Definition, found by its alias.
     *
     * @param string $alias
     * @return Definition
     * @throws \Fojuth\Stamp\Template\Exception\DefinitionNotFoundException
     */
    public function getDefinition($alias)
    {
        foreach ($this->definitions as $definition) {
            if ($definition['alias'] === $alias) {
                return new Definition($definition);
            }
        }

        throw new DefinitionNotFoundException($alias);
    }
}
