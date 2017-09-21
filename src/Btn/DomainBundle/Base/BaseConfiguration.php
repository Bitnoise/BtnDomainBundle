<?php

namespace Btn\DomainBundle\Base;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Convenient base configuration class to use together with the BaseExtension.
 *
 *
 */
abstract class BaseConfiguration implements ConfigurationInterface
{
    private $alias;

    /**
     * @param string $alias
     */
    public function __construct($alias)
    {
        $this->alias = $alias;
    }

    /**
     * {@inheritdoc}
     */
    final public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $this->addNodeDefinitions($treeBuilder->root($this->alias));

        return $treeBuilder;
    }

    abstract protected function addNodeDefinitions($rootNode);
}
