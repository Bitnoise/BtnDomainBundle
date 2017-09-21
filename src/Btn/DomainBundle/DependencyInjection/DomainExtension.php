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
        $loader->tryLoadForExtension('security');
        $loader->tryLoadForExtension('doctrine');
        $loader->tryLoadForExtension('doctrine_orm_bridge');
        $loader->tryLoadForExtension('doctrine_migrations');
        $loader->tryLoadForExtension('jms_serializer');
        $loader->load('assets');

        $env = $container->getParameter('kernel.environment');

        if ('test' === $env) {
            $loader->tryLoadForExtension('doctrine', 'doctrine_test');
        }

        $container->setParameter('huel.domain_bundle.src_dir', realpath(__DIR__ . '/../../../../src/Huel'));
    }

    /**
     * {@inheritdoc}
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('aliases.yml');
        $loader->load('command_bus.yml');
        $loader->load('command_handler.yml');
        $loader->load('listener.yml');
        $loader->load('repository.yml');
        $loader->load('services.yml');

        if ($mergedConfig['persistence']['orm']['enabled']) {
            $container->setParameter($this->getAlias() . '.persistence.orm.enabled', true);
        }
    }
}
