<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @runTestsInSeparateProcesses
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
abstract class FormTestCase extends WebTestCase
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        foreach (static::getSymfonyOptions() as $option => $value) {
            $_SERVER['SYMFONY__'.$option] = $value;
        }
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();

        $kernel = self::createKernel();
        $fs = new Filesystem();
        $fs->remove($kernel->getCacheDir());
        $kernel->shutdown();
        static::$kernel = null;
    }

    protected static function getSymfonyOptions()
    {
        return array();
    }

    protected function fetch_application_page_javascript($url, $client = null)
    {
        $client = $client ?: self::createClient(array());

        $crawler = $client->request('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $elt = $crawler->filterXPath('//script[@id="validation_script"]');

        $this->assertEquals(1, $elt->count());

        return $elt->html();
    }

    protected function assertEqualJs($excepted, $actual)
    {
        $this->assertEquals(
            $this->stripWhiteSpace($excepted),
            $this->stripWhiteSpace($actual)
        );
    }

    protected function stripWhiteSpace($js)
    {
        $js = str_replace(
            array(',', '{', '}', ':', ';', 'function'),
            array(', ', ' { ', ' } ', ' : ', '; ', ' function '),
            trim($js)
        );

        return preg_replace('/(\s+|\n)/', ' ', trim($js));
    }
}
