<?php
namespace Boekkooi\Bundle\JqueryValidationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class ExtensionPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container)
    {
        $this->registerRuleProcessors($container);
        $this->registerRuleCompilers($container);
        $this->registerRuleMappers($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function registerRuleProcessors(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('boekkooi.jquery_validation.rule_processor')) {
            return;
        }

        $passes = new \SplPriorityQueue();
        $order = PHP_INT_MAX;
        foreach ($container->findTaggedServiceIds('form_rule_processor') as $id => $attr) {
            $priority = isset($attr[0]['priority']) ? $attr[0]['priority'] : 0;

            $passes->insert($id, array($priority, --$order));
        }

        $references = array();
        foreach ($passes as $id) {
            $references[] = new Reference($id);
        }
        $container->getDefinition('boekkooi.jquery_validation.rule_processor')->replaceArgument(0, $references);
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function registerRuleCompilers(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('boekkooi.jquery_validation.rule_compiler')) {
            return;
        }

        $passes = new \SplPriorityQueue();
        $order = PHP_INT_MAX;
        foreach ($container->findTaggedServiceIds('form_rule_compiler') as $id => $attr) {
            $priority = isset($attr[0]['priority']) ? $attr[0]['priority'] : 0;

            $passes->insert($id, array($priority, --$order));
        }

        $references = array();
        foreach ($passes as $id) {
            $references[] = new Reference($id);
        }
        $container->getDefinition('boekkooi.jquery_validation.rule_compiler')->replaceArgument(0, $references);
    }

    /**
     * @param ContainerBuilder $container
     * @return array|null
     */
    protected function registerRuleMappers(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('boekkooi.jquery_validation.form.rule.processor.constraint_mapper')) {
            return;
        }

        $mappers = $container->findTaggedServiceIds('form_rule_constraint_mapper');
        $resolverDef = $container->getDefinition('boekkooi.jquery_validation.form.rule.processor.constraint_mapper');
        foreach ($mappers as $id => $attr) {
            $resolverDef->addMethodCall('addMapper', array(new Reference($id)));
        }
    }
}
