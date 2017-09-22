<?php

namespace Btn\DomainBundle\DependencyInjection\Compiler;

use Btn\Domain\MessageBus\CommandBusAwareTrait;
use Btn\Domain\MessageBus\EventBusAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class TraitInjectorMap
{
    public static function getMap()
    {
        return [
            ContainerAwareTrait::class  => ['setContainer', 'service_container'],
            CommandBusAwareTrait::class => ['setCommandBus', 'command_bus'],
            EventBusAwareTrait::class   => ['setEventBus', 'event_bus'],
        ];
    }
}
