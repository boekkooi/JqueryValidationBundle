<?php
namespace Boekkooi\Bundle\JqueryValidationBundle;

use Boekkooi\Bundle\JqueryValidationBundle\DependencyInjection\Compiler\ExtensionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class BoekkooiJqueryValidationBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new ExtensionPass());
    }
}
