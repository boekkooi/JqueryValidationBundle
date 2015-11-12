<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('boekkooi_jquery_validation');
        $rootNode->append($this->loadFormNode());
        $rootNode->append($this->loadTwigNode());

        return $treeBuilder;
    }

    private function loadFormNode()
    {
        $treeBuilder = new TreeBuilder();

        $node = $treeBuilder->root('form');
        $node
            ->treatTrueLike(array('enabled' => true, 'additional' => true))
            ->treatFalseLike(array('enabled' => false))
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enabled')
                    ->info('Set to false to disable the form constraints being parsed/converted by default')
                    ->defaultTrue()
                ->end()
                ->arrayNode('additionals')
                    ->beforeNormalization()->ifString()->then(function ($v) { return strtolower($v) === 'true'; })->end()
                    ->treatTrueLike(array(
                        'accept' => true,
                        'ipv4' => true,
                        'ipv6' => true,
                        'iban' => true,
                        'luhn' => true,
                        'pattern' => true,
                        'time' => true,
                        'one_or_other' => true,
                        'required_group' => true,
                        'is_boolean' => true
                    ))
                    ->treatFalseLike(array())
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->booleanNode('accept')->defaultFalse()->end()
                        ->booleanNode('ipv4')->defaultFalse()->end()
                        ->booleanNode('ipv6')->defaultFalse()->end()
                        ->booleanNode('iban')->defaultFalse()->end()
                        ->booleanNode('luhn')->defaultFalse()->end()
                        ->booleanNode('pattern')->defaultFalse()->end()
                        ->booleanNode('time')->defaultFalse()->end()
                        ->booleanNode('one_or_other')->defaultFalse()->end()
                        ->booleanNode('required_group')->defaultFalse()->end()
                        ->booleanNode('is_boolean')->defaultFalse()->end()
                    ->end()
                ->end()
            ->end();

        return $node;
    }

    private function loadTwigNode()
    {
        $treeBuilder = new TreeBuilder();

        $node = $treeBuilder->root('twig');
        $node
            ->treatTrueLike(array())
            ->treatFalseLike(array())
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enabled')->defaultTrue()->end()
            ->end();

        return $node;
    }
}
