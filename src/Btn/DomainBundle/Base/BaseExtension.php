<?php

namespace Btn\DomainBundle\Base;

use Btn\DomainBundle\Loader\ConfigLoader;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Config\FileLocator;

/**
 * Convenient base extension which passes the bundle alias to the configuration.
 *
 *
 */
abstract class BaseExtension extends ConfigurableExtension implements PrependExtensionInterface
{
    /** @var \ReflectionClass $reflectionClass $reflectionClass */
    protected $reflectionClass;
    /** @var array $resourceDir common bundle config directory */
    protected $resourceDir = '/../Resources/config';

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        $namespace = $this->getReflectionClass()->getNamespaceName();

        $class = $namespace . '\\Configuration';
        if (!class_exists($class)) {
            return null;
        }

        $ref = new \ReflectionClass($class);
        $container->addResource(new FileResource($ref->getFileName()));

        $configuration = new $class($this->getAlias());

        return $configuration;
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
    }

    /**
     * @param ContainerBuilder $container
     * @param null             $rootDir
     * @param null             $resourceDir
     *
     * @return ConfigLoader
     */
    protected function getConfigLoader(ContainerBuilder $container, $rootDir = null, $resourceDir = null)
    {
        if (null === $rootDir) {
            $fileName = $this->getReflectionClass()->getFileName();
            $rootDir = substr($fileName, 0, strrpos($fileName, DIRECTORY_SEPARATOR));
        }
        if (null === $resourceDir) {
            $resourceDir = $this->resourceDir;
        }
        $loader = new ConfigLoader($container, new FileLocator($rootDir . $resourceDir));

        return $loader;
    }

    protected function getReflectionClass()
    {
        if (!$this->reflectionClass) {
            $this->reflectionClass = new \ReflectionClass($this);
        }

        return $this->reflectionClass;
    }
}
