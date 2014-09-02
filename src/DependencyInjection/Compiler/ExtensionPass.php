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
        $this->registerRuleFormPasses($container);
        $this->registerRuleMappers($container);
    }

    /**
     * @param ContainerBuilder $container
     * @return array
     */
    protected function registerRuleMappers(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('boekkooi.jquery_validation.constraint_resolver')) {
            return;
        }

        $mappers = $container->findTaggedServiceIds('validator.rule_mapper');
        $resolverDef = $container->getDefinition('boekkooi.jquery_validation.constraint_resolver');
        $resolverDef->addMethodCall('addDefaultMappers');
        foreach ($mappers as $id => $attr) {
            $resolverDef->addMethodCall('addMapper', array(new Reference($id)));
        }
    }

    /**
     * @param ContainerBuilder $container
     */
    protected function registerRuleFormPasses(ContainerBuilder $container)
    {
        $passes = new \SplPriorityQueue();
        $order = PHP_INT_MAX;
        foreach ($container->findTaggedServiceIds('validator.rule_pass') as $id => $attr) {
            $priority = isset($attr['priority']) ? intval($attr['priority']) : 0;

            $passes->insert($id, array($priority, --$order));
        }

        $references = array();
        foreach ($passes as $id) {
            $references[] = new Reference($id);
        }
        $container->getDefinition('boekkooi.jquery_validation.rule_collector')->replaceArgument(0, $references);
    }
}
