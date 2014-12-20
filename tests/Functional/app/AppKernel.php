<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),

            new Boekkooi\Bundle\JqueryValidationBundle\BoekkooiJqueryValidationBundle(),

            new Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional\TestBundle\TestBundle()
        );

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        if ($this->getEnvironment() === 'test') {
            $loader->load(__DIR__ . '/config/config_test.yml');
            return;
        }
        $loader->load(__DIR__ . '/config/config.yml');
    }

    /**
     * @return string
     */
    public function getCacheDir()
    {
        return sys_get_temp_dir().'/BoekkooiJqueryValidationBundle/cache';
    }

    /**
     * @return string
     */
    public function getLogDir()
    {
        return sys_get_temp_dir().'/BoekkooiJqueryValidationBundle/logs';
    }
}
