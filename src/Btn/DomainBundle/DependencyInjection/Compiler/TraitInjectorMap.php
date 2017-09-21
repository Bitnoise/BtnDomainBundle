<?php

namespace Btn\DomainBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class TraitInjectorMap
{
    public static function getMap()
    {
        return [
            ContainerAwareTrait::class => ['setContainer', 'service_container'],
        ];
    }
}
