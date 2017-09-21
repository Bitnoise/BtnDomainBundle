<?php

namespace Btn\DomainBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

abstract class AbstractCompilerPass implements CompilerPassInterface
{
    /**
     * @source http://php.net/manual/pl/function.class-uses.php
     *
     * @param      $class
     * @param bool $autoload
     *
     * @return array
     */
    protected static function classUsesDeep($class, $autoload = true)
    {
        $traits = [];

        // Get traits of all parent classes
        do {
            $traits = array_merge(class_uses($class, $autoload), $traits);
        } while ($class = get_parent_class($class));

        // Get traits of all parent traits
        $traitsToSearch = $traits;
        while (!empty($traitsToSearch)) {
            $newTraits = class_uses(array_pop($traitsToSearch), $autoload);
            $traits = array_merge($newTraits, $traits);
            $traitsToSearch = array_merge($newTraits, $traitsToSearch);
        }

        foreach (array_keys($traits) as $trait) {
            $traits = array_merge(class_uses($trait, $autoload), $traits);
        }

        return array_unique($traits);
    }

    protected function getServiceClassName(ContainerBuilder $container, $id)
    {
        $definition = $container->getDefinition($id);

        $class = $definition->getClass();

        if (!$class) {
            return;
        }

        if (preg_match('~^%(.+)%$~', $class, $match)) {
            $class = $container->getParameter($match[1]);
        }

        if (!class_exists($class)) {
            return;
        }

        return $class;
    }

    /**
     * @param Definition $definition
     * @param string     $methodName
     *
     * @return bool
     */
    protected function hasMethodCall(Definition $definition, $methodName)
    {
        $hasMethodCall = array_filter($definition->getMethodCalls(), function ($calls) use ($methodName) {
            return $calls[0] === $methodName;
        });

        return $hasMethodCall ? true : false;
    }
}
