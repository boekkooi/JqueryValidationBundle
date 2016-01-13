<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle;

use Boekkooi\Bundle\JqueryValidationBundle\BoekkooiJqueryValidationBundle;
use Boekkooi\Bundle\JqueryValidationBundle\DependencyInjection\Compiler\ExtensionPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @covers Boekkooi\Bundle\JqueryValidationBundle\BoekkooiJqueryValidationBundle
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class BoekkooiJqueryValidationBundleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_should_add_the_extension_pass()
    {
        try {
            $container = $this->getMock(ContainerBuilder::class);
            $container->expects($this->once())
                ->method('addCompilerPass')
                ->with($this->isInstanceOf(ExtensionPass::class));

            $SUT = new BoekkooiJqueryValidationBundle();
            $SUT->build($container);
        } catch (\PHPUnit_Framework_MockObject_RuntimeException $e) {
            $this->markTestSkipped($e->getMessage());
        }
    }
}
