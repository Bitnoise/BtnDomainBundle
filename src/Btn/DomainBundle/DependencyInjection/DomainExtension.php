<?php

namespace Btn\DomainBundle\DependencyInjection;

use Btn\DomainBundle\Base\BaseExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader;

class DomainExtension extends BaseExtension
{
    /**
     * @return string
     */
    public function getAlias()
    {
        return 'domain';
    }

    /**
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        parent::prepend($container);
//         get config loader
        $loader = $this->getConfigLoader($container);
        $loader->tryLoadForExtension('jms_serializer');
    }

    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('command_bus.yml');
        $loader->load('command_handler.yml');
    }
}
