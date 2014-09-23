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

    /**
     * @test
     */
    public function it_should_render_valid_javascript_for_a_form_with_buttons()
    {
        $javascript = $this->fetch_application_page_javascript('/buttons');
        $this->assertEqualJs(
            '(function ($) {
                var form = $("form[name=\"buttons\"]");
                var groups = {"Default": false, "main": false};

                form.find("*[name=\"buttons\x5BdefaultValidation\x5D\"]").click(function () {
                    groups = {"Default": true, "main": false};
                });
                form.find("*[name=\"buttons\x5BmainValidation\x5D\"]").click(function () {
                    groups = {"Default": false, "main": true};
                });
                form.find("*[name=\"buttons\x5BmainAndDefaultValidation\x5D\"]").click(function () {
                    groups = {"Default": true, "main": true};
                });
                form.find("*[name=\"buttons\x5BnoValidation\x5D\"]").addClass("cancel");

                form.validate({
                    rules: {
                        "buttons\x5Btitle\x5D": {
                            "required": {
                                depends: function () {
                                    return groups["main"] || groups["Default"];
                                }
                            }, "minlength": {
                                param: 8, depends: function () {
                                    return groups["main"];
                                }
                            }, "maxlength": {
                                param: 200, depends: function () {
                                    return groups["main"];
                                }
                            }
                        }, "buttons\x5Bcontent\x5D": {
                            "required": {
                                depends: function () {
                                    return groups["Default"];
                                }
                            }
                        }
                    },
                    messages: {
                        "buttons\x5Btitle\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x208\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x20200\x20characters\x20or\x20less."
                        }, "buttons\x5Bcontent\x5D": {"required": "This\x20value\x20should\x20not\x20be\x20blank."}
                    }
                });
            })(jQuery);',
            $javascript
        );
    }

    /**
     * @test
     */
    public function it_should_render_collection_row_javascript()
    {
        $client = self::createClient();

        $javascript = $this->fetch_application_page_javascript('/collection', $client);

        $this->assertEqualJs(
            '(function ($) {
                var form = $("form[name=\"collection\"]");
                form.validate({rules: {"collection\x5Btitle\x5D": {"required": true, "minlength": 8, "maxlength": 200}},
                    messages: {
                        "collection\x5Btitle\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x208\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x20200\x20characters\x20or\x20less."
                        }
                    }
                });
            })(jQuery);',
            $javascript
        );

        $elt = $client->getCrawler()->filterXPath('//div/@data-prototype-js');
        $javascriptPrototype = $elt->html();

        $this->assertEqualJs(
            '(function ($) {
                var form = $("form[name=\"collection\"]");
                form.find("*[name=\"collection\x5Btags\x5D\x5Btag__name__\x5D\"]").rules("add", {
                    "required": true,
                    "messages": {"required": "This\x20value\x20should\x20not\x20be\x20blank."}
                });
            })(jQuery);',
            $javascriptPrototype
        );
    }

    /**
     * @test
     */
    public function it_should_render_collection_compound_row_javascript()
    {
        $client = self::createClient();

        $javascript = $this->fetch_application_page_javascript('/collection_compound', $client);

        $this->assertEqualJs(
            '(function ($) {
                var form = $("form[name=\"collection_compound\"]");
                form.validate({rules: {"collection_compound\x5Btitle\x5D": {"required": true, "minlength": 8, "maxlength": 200}},
                    messages: {
                        "collection_compound\x5Btitle\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x208\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x20200\x20characters\x20or\x20less."
                        }
                    }
                });
            })(jQuery);',
            $javascript
        );

        $elt = $client->getCrawler()->filterXPath('//div/@data-prototype-js');
        $javascriptPrototype = $elt->html();

        $this->assertEqualJs(
            '(function ($) {
                var form = $("form[name=\"collection_compound\"]");
                form.find("*[name=\"collection_compound\x5Btags\x5D\x5B__name__\x5D\x5Bname\x5D\"]").rules("add", {
                    "required": true,
                    "minlength": 2,
                    "messages": {
                    "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                        "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x202\x20characters\x20or\x20more."
                    }
                });
                form.find("*[name=\"collection_compound\x5Btags\x5D\x5B__name__\x5D\x5Bpassword\x5D\x5Bfirst\x5D\"]").rules("add", {
                    "required": true,
                    "messages": {"required": "This\x20value\x20should\x20not\x20be\x20blank."}
                });
                form.find("*[name=\"collection_compound\x5Btags\x5D\x5B__name__\x5D\x5Bpassword\x5D\x5Bsecond\x5D\"]").rules("add", {
                    "equalTo": "form[name=\"collection_compound\"] *[name=\"collection_compound[tags][__name__][password][first]\"]",
                    "messages": {"equalTo": "WRONG\x21"}
                });
            })(jQuery);',
            $javascriptPrototype
        );
    }


    protected function fetch_application_page_javascript($url, $client = null)
    {
        $client = $client ?: self::createClient();

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
