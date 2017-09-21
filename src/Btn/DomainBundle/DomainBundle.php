<?php

namespace Btn\DomainBundle;

use Btn\DomainBundle\DependencyInjection\Compiler\TraitInjectorMap;
use Btn\DomainBundle\DependencyInjection\DomainExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Btn\DomainBundle\DependencyInjection\Compiler;

class DomainBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new Compiler\CommandBusCompilerPass());
        $container->addCompilerPass(new Compiler\TraitInjectorCompilerPass(TraitInjectorMap::getMap()));
    }


    public function getContainerExtension()
    {
        if ($this->extension === null) {
            $this->extension = new DomainExtension();
        }

        return $this->extension;
    }
}
