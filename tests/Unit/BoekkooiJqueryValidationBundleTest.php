<?php
namespace Tests\Unit\Boekkooi\Bundle\JqueryValidationBundle;

use Boekkooi\Bundle\JqueryValidationBundle\BoekkooiJqueryValidationBundle;

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
        if (!class_exists('PHPUnit_Runner_Version') || version_compare('4.4', \PHPUnit_Runner_Version::id(), '<')) {
            $this->markTestSkipped('Need phpunit >= 4.4 to run this test');
        }

        $container = $this->getMock('Symfony\Component\DependencyInjection\ContainerBuilder');
        $container->expects($this->once())
            ->method('addCompilerPass')
            ->with($this->isInstanceOf('Boekkooi\Bundle\JqueryValidationBundle\DependencyInjection\Compiler\ExtensionPass'));

        $SUT = new BoekkooiJqueryValidationBundle();
        $SUT->build($container);
    }
}
