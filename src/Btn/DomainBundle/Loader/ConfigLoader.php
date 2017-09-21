<?php

namespace Btn\DomainBundle\Loader;

use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ConfigLoader extends YamlFileLoader
{
    /**
     * {@inheritdoc}
     */
    public function load($file, $type = null)
    {
        $ext = substr($file, strrpos($file, '.') + 1);
        if ('yml' !== $ext) {
            $file .= '.yml';
        }

        return parent::load($file);
    }

    /**
     * @param string $file
     * @param null   $type
     */
    public function tryLoad($file, $type = null)
    {
        try {
            return $this->load($file, $type);
        } catch (\InvalidArgumentException $e) {
            // silently got through if file could not be loaded
        }
    }

    /**
     * @param array $fileArray
     * @param null  $type
     */
    public function tryLoadFromArray(array $fileArray, $type = null)
    {
        foreach ($fileArray as $file) {
            $this->tryLoad($file, $type);
        }
    }

    /**
     * @param      $extension
     * @param null $file
     * @param null $type
     */
    public function tryLoadForExtension($extension, $file = null, $type = null)
    {
        if ($this->container->hasExtension($extension)) {
            if (null === $file) {
                $file = $extension;
            }

            return $this->tryLoad($file, $type);
        }
    }
}
