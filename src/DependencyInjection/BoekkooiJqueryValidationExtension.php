<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class BoekkooiJqueryValidationExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), $config);

        $this->configureForm($container, $config, $loader);
        $this->configureFormAdditionals($container, $config, $loader);
        $this->configureTwig($config, $loader);
    }

    /**
     * @param array $config
     * @param $loader
     */
    private function configureTwig(array $config, LoaderInterface $loader)
    {
        if ($config['twig']['enabled']) {
            $loader->load('twig.yml');
        }
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     * @param LoaderInterface $loader
     */
    private function configureForm(ContainerBuilder $container, array $config, LoaderInterface $loader)
    {
        $container->setParameter('boekkooi.jquery_validation.enabled', $config['form']['enabled']);

        $loader->load('form_rule_processors.yml');
        $loader->load('form_rule_mappers.yml');
        $loader->load('form_rule_compilers.yml');
    }

    /**
     * @param array $config
     * @param ContainerBuilder $container
     * @param LoaderInterface $loader
     */
    private function configureFormAdditionals(ContainerBuilder $container, array $config, LoaderInterface $loader)
    {
        $includeAdditional = false;
        foreach ($config['form']['additionals'] as $name => $active) {
            $container->setParameter(
                sprintf('boekkooi.jquery_validation.additional.%s', $name),
                $active
            );
            $includeAdditional = $includeAdditional || $active;
        }

        if ($includeAdditional) {
            $loader->load('form_rule_additional_mappers.yml');
        }
    }
}
