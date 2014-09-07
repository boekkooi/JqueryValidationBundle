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
            ->treatTrueLike(array('enabled' => true))
            ->treatFalseLike(array('enabled' => true))
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enabled')->defaultTrue()->end()
            ->end();

        return $node;
    }

    private function loadTwigNode()
    {
        $treeBuilder = new TreeBuilder();

        $node = $treeBuilder->root('twig');
        $node
            ->treatTrueLike(array('enabled' => true))
            ->treatFalseLike(array('enabled' => true))
            ->addDefaultsIfNotSet()
            ->children()
                ->booleanNode('enabled')->defaultTrue()->end()
            ->end();

        return $node;
    }
}
