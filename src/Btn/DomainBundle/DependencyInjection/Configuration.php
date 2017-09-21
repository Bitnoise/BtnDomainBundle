<?php

namespace Btn\DomainBundle\DependencyInjection;

use Btn\DomainBundle\Base\BaseConfiguration;

class Configuration extends BaseConfiguration
{
    protected function addNodeDefinitions($rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('persistence')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('orm')
                            ->canBeDisabled()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
