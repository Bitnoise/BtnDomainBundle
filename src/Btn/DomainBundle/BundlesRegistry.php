<?php

namespace Btn\DomainBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use JMS\AopBundle\JMSAopBundle;
use JMS\DiExtraBundle\JMSDiExtraBundle;
use JMS\SerializerBundle\JMSSerializerBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use SimpleBus\SymfonyBridge\SimpleBusCommandBusBundle;
use SimpleBus\SymfonyBridge\SimpleBusEventBusBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
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

            new JMSSerializerBundle(),
            new JMSDiExtraBundle($kernel),
            new JMSAopBundle(),

            new SimpleBusCommandBusBundle(),
            new SimpleBusEventBusBundle(),
        ];

        return $bundles;
    }
}
