<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @coversNothing
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class SimpleFormTest extends WebTestCase
{
    /**
     * @test
     */
    public function it_should_render_valid_form_javascript()
    {
        $javascript = $this->fetch_application_page_javascript('/');
        $this->assertEqualJs(
            '(function ($) {
                var form = $("form[name=\"simple_form\"]");
                form.validate({
                    rules: {
                        "simple_form\x5Bname\x5D": {"required": true, "minlength": 2},
                        "simple_form\x5Bpassword\x5D\x5Bfirst\x5D": {"required": true},
                        "simple_form\x5Bpassword\x5D\x5Bsecond\x5D": {"equalTo": "form[name=\"simple_form\"] *[name=\"simple_form[password][first]\"]"}
                    },
                    messages: {
                        "simple_form\x5Bname\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x202\x20characters\x20or\x20more."
                        },
                        "simple_form\x5Bpassword\x5D\x5Bfirst\x5D": {"required": "This\x20value\x20should\x20not\x20be\x20blank."},
                        "simple_form\x5Bpassword\x5D\x5Bsecond\x5D": {"equalTo": "WRONG\x21"}
                    }
                });
            })(jQuery);',
            $javascript
        );
    }

    /**
     * @test
     */
    public function it_should_render_valid_data_form_javascript()
    {
        $javascript = $this->fetch_application_page_javascript('/simple_data');
        $this->assertEqualJs(
            '(function ($) {
                var form = $("form[name=\"simple_form_data\"]");
                form.validate({
                    rules: {
                        "simple_form_data\x5Bname\x5D": {"required": true, "minlength": "2", "maxlength": "255"},
                        "simple_form_data\x5Bpassword\x5D\x5Bfirst\x5D": {"required": true, "minlength": "2", "maxlength": "255"},
                        "simple_form_data\x5Bpassword\x5D\x5Bsecond\x5D": {"equalTo": "form[name=\"simple_form_data\"] *[name=\"simple_form_data[password][first]\"]"}
                    },
                    messages: {
                        "simple_form_data\x5Bname\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x202\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x20255\x20characters\x20or\x20less."
                        },
                        "simple_form_data\x5Bpassword\x5D\x5Bfirst\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x202\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x20255\x20characters\x20or\x20less."
                        },
                        "simple_form_data\x5Bpassword\x5D\x5Bsecond\x5D": {"equalTo": "This\x20value\x20is\x20not\x20valid."}
                    }
                });
            })(jQuery);',
            $javascript
        );
    }

    protected function fetch_application_page_javascript($url)
    {
        $client = self::createClient();

        $crawler = $client->request('GET', $url);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $elt = $crawler->filterXPath('//script[@id="validation_script"]');

        $this->assertEquals(1, $elt->count());
        return $elt->html();
    }

    protected function assertEqualJs($excepted, $actual)
    {
        $this->assertEquals($this->stripWhiteSpace($excepted), $this->stripWhiteSpace($actual));
    }

    protected function stripWhiteSpace($js)
    {
        return preg_replace('/\s+/', '' , trim($js));
    }
}
