<?php

namespace Btn\DomainBundle\DependencyInjection\Compiler;

use JMS\DiExtraBundle\Metadata\DefaultNamingStrategy;
use JMS\DiExtraBundle\Metadata\NamingStrategy;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Finder\Finder;

class CommandBusCompilerPass extends AbstractCompilerPass
{
    /** @var array */
    private $registeredHandlers = [];
    /** @var array */
    private $registeredValidators = [];
    /** @var NamingStrategy */
    private $idGenerator;

    public function __construct()
    {
        $this->idGenerator = new DefaultNamingStrategy();
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->handleTaggedServices($container);
        $this->autoDiscoverServices($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function handleTaggedServices(ContainerBuilder $container)
    {
        $commandBusHandlers = $container->findTaggedServiceIds('domain.command_handler');

        foreach (array_keys($commandBusHandlers) as $id) {
            $definition = $container->getDefinition($id);
            $class = $this->getServiceClassName($container, $id);
            $this->addCommandHandlerTag($definition, $class);
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    private function autoDiscoverServices(ContainerBuilder $container)
    {
        $finder = new Finder();
        try {
            $finder->files()->in(__DIR__ . '/../../../Domain/*/Command/')->name('/(Handler|Validator)\.php$/');
        }catch(\InvalidArgumentException $e){
            $finder = [];
        }
        foreach ($finder as $file) {
            if (!preg_match('~(Domain/[^/]+/Command/(.*)(Handler|Validator))\.php$~', $file->getRealPath(), $matches)) {
                continue;
            }

            $class = str_replace('/', '\\', $matches[1]);

            $refClass = new \ReflectionClass($class);
            if ($refClass->isAbstract()) {
                continue;
            }

            switch ($matches[3]) {
                case 'Handler':
                    $this->registerHandler($container, $class);
                    break;
                case 'Validator':
                    $this->registerValidator($container, $class);
                    break;
            }
        }
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    private function isRegisteredValidator($class)
    {
        return array_search($class, $this->registeredValidators) ? true : false;
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $class
     */
    private function registerValidator(ContainerBuilder $container, $class)
    {
        if ($this->isRegisteredValidator($class)) {
            return;
        }

        $this->registeredValidators[] = $class;

        $definition = new Definition($class);
        $id = $this->addDefinition($container, $definition);

        $resolverDefinition = $container->getDefinition('command_bus.validator.resolver');
        $resolverDefinition->addMethodCall('addValidator', [
            new Reference($id),
            preg_replace('~Validator~', '', $class),
        ]);
    }

    /**
     * @param string $class
     *
     * @return bool
     */
    private function isRegisteredHandler($class)
    {
        return array_search($class, $this->registeredHandlers) ? true : false;
    }

    /**
     * @param ContainerBuilder $container
     * @param string           $class
     */
    private function registerHandler(ContainerBuilder $container, $class)
    {
        if ($this->isRegisteredHandler($class)) {
            return;
        }

        $definition = new Definition($class);
        $this->addDefinition($container, $definition);
        $this->addCommandHandlerTag($definition, $class);
    }

    /**
     * @param Definition $definition
     * @param string     $class
     */
    private function addCommandHandlerTag(Definition $definition, $class)
    {
        if ($this->isRegisteredHandler($class)) {
            return;
        }

        $this->registeredHandlers[] = $class;

        $definition->addTag('command_handler', [
            'handles' => preg_replace('~Handler$~', '', $class),
        ]);
    }

    /**
     * @param ContainerBuilder $container
     * @param Definition       $definition
     *
     * @return string
     */
    private function addDefinition(ContainerBuilder $container, Definition $definition)
    {
        $id = $this->idGenerator->classToServiceName($definition->getClass());
        $container->addDefinitions([$id => $definition]);

        return $id;
    }
}
