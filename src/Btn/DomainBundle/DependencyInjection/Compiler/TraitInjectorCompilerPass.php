<?php

namespace Btn\DomainBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TraitInjectorCompilerPass extends AbstractCompilerPass
{
    /** @var array */
    private $traitMap;

    /**
     * @param array $traitMap
     */
    public function __construct(array $traitMap = null)
    {
        $this->traitMap = $traitMap ?: TraitInjectorMap::getMap();
    }

    public function process(ContainerBuilder $container)
    {
        foreach (array_keys($container->getDefinitions()) as $id) {
            $class = $this->getServiceClassName($container, $id);
            if (!$class) {
                continue;
            }

            $definition = $container->getDefinition($id);

            $classUses = self::classUsesDeep($class);

            foreach ($this->traitMap as $repositoryClass => $params) {
                if (!array_search($repositoryClass, $classUses)) {
                    continue;
                }

                if ($this->hasMethodCall($definition, $params[0])) {
                    continue;
                }

                $definition->addMethodCall($params[0], [new Reference($params[1])]);
            }
        }
    }
}
