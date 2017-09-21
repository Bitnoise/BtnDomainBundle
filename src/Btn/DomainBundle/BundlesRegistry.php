<?php

namespace Btn\DomainBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle;
use JMS\AopBundle\JMSAopBundle;
use JMS\DiExtraBundle\JMSDiExtraBundle;
use JMS\SerializerBundle\JMSSerializerBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle;
use SimpleBus\SymfonyBridge\SimpleBusCommandBusBundle;
use SimpleBus\SymfonyBridge\SimpleBusEventBusBundle;
use Symfony\Bundle\DebugBundle\DebugBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Bundle\WebProfilerBundle\WebProfilerBundle;
use Symfony\Component\HttpKernel\Kernel;

/**
 * SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BundlesRegistry
{
    /**
     * @param Kernel $kernel
     *
     * @return array
     */
    public static function getBundles(Kernel $kernel)
    {
        $bundles = [
            new FrameworkBundle(),
            new SecurityBundle(),
            new TwigBundle(),
            new SwiftmailerBundle(),
            new MonologBundle(),

            new SensioFrameworkExtraBundle(),
            new DoctrineBundle(),
            new DoctrineMigrationsBundle(),

            new JMSSerializerBundle(),
            new JMSDiExtraBundle($kernel),
            new JMSAopBundle(),

            new SimpleBusCommandBusBundle(),
            new SimpleBusEventBusBundle(),
        ];

        if (in_array($kernel->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new DebugBundle();
            $bundles[] = new WebProfilerBundle();
            $bundles[] = new SensioGeneratorBundle();
        }

        return $bundles;
    }
}
