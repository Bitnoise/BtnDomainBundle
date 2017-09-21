<?php

namespace Btn\DomainBundle;

use Symfony\Component\HttpKernel\Kernel;

abstract class AbstractKernel extends Kernel
{
    /**
     * {@inheritdoc}
     */
    public function registerBundles()
    {
        $bundles = BundlesRegistry::getBundles($this);

        return $bundles;
    }
}
