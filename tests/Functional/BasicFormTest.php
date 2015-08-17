<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional;

/**
 * @coversNothing
 * @runTestsInSeparateProcesses
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class BasicFormTest extends FormTestCase
{
    protected static function getSymfonyOptions()
    {
        return array(
            'enable_additionals' => false,
        );
    }

    /**
     * @test
     */
    public function it_should_render_valid_form_javascript()
    {
        $javascript = $this->fetch_application_page_javascript('/');
        $this->assertEqualJs(
            '(function ($) {
                "use strict";
                var form = $("form[name=\"simple_form\"]");
                var validator = form.validate({
                    rules: {
                        "simple_form\x5Bname\x5D": {"required": true, "minlength": 2},
                        "simple_form\x5Bpassword\x5D\x5Bfirst\x5D": {"required": true},
                        "simple_form\x5Bpassword\x5D\x5Bsecond\x5D": {
                            "equalTo": {
                                param: "form[name=\"simple_form\"] *[name=\"simple_form[password][first]\"]",
                                depends: function () {
                                    if (("simple_form\x5Bpassword\x5D\x5Bfirst\x5D" in validator.errorMap || "simple_form\x5Bpassword\x5D\x5Bfirst\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        }
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
                "use strict";
                var form = $("form[name=\"simple_form_data\"]");
                var validator = form.validate({
                    rules: {
                        "simple_form_data\x5Bname\x5D": {"required": true, "minlength": "2", "maxlength": "255"},
                        "simple_form_data\x5Bpassword\x5D\x5Bfirst\x5D": {"required": true, "minlength": "2", "maxlength": "255"},
                        "simple_form_data\x5Bpassword\x5D\x5Bsecond\x5D": {
                            "equalTo": {
                                param: "form[name=\"simple_form_data\"] *[name=\"simple_form_data[password][first]\"]",
                                depends: function () {
                                    if (("simple_form_data\x5Bpassword\x5D\x5Bfirst\x5D" in validator.errorMap || "simple_form_data\x5Bpassword\x5D\x5Bfirst\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        }
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
                "use strict";
                var form = $("form[name=\"buttons\"]");
                var validator = form.validate({
                    rules: {
                        "buttons\x5Btitle\x5D": {
                            "required": {
                                depends: function () {
                                    return (validator.settings.validation_groups["main"] || validator.settings.validation_groups["Default"]);
                                }
                            },
                            "minlength": {
                                param: 8, depends: function () {
                                    return (validator.settings.validation_groups["main"]);
                                }
                            },
                            "maxlength": {
                                param: 200, depends: function () {
                                    return (validator.settings.validation_groups["main"]);
                                }
                            }
                        },
                        "buttons\x5Bcontent\x5D": {
                            "required": {
                                depends: function () {
                                    return (validator.settings.validation_groups["Default"]);
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

                validator.settings.validation_groups = {"Default": false, "main": false};

                form.find("*[name=\"buttons\x5BdefaultValidation\x5D\"]").click(function () {
                    validator.settings.validation_groups = {"Default": true, "main": false};
                });
                form.find("*[name=\"buttons\x5BmainValidation\x5D\"]").click(function () {
                    validator.settings.validation_groups = {"Default": false, "main": true};
                });
                form.find("*[name=\"buttons\x5BmainAndDefaultValidation\x5D\"]").click(function () {
                    validator.settings.validation_groups = {"Default": true, "main": true};
                });
                form.find("*[name=\"buttons\x5BnoValidation\x5D\"]").addClass("cancel");
            })(jQuery);',
            $javascript
        );
    }

    /**
     * @test
     */
    public function it_should_render_collection_row_groups_javascript()
    {
        $client = self::createClient();

        $javascript = $this->fetch_application_page_javascript('/collection_groups', $client);

        $this->assertEqualJs(
            '(function ($) {
                "use strict";
                var form = $("form[name=\"collection\"]");
                var validator = form.validate({
                    rules: {
                        "collection\x5Btitle\x5D": {
                            "required": {
                                depends: function () {
                                    return (validator.settings.validation_groups["Default"]);
                                }
                            }, "minlength": {
                                param: 8,
                                depends: function () {
                                    return (validator.settings.validation_groups["main"]);
                                }
                            }, "maxlength": {
                                param: 200,
                                depends: function () {
                                    return (validator.settings.validation_groups["main"]);
                                }
                            }
                        }
                    },
                    messages: {
                        "collection\x5Btitle\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x208\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x20200\x20characters\x20or\x20less."
                        }
                    }
                });
                validator.settings.validation_groups = {"Default": false, "main": false};
                form.find("*[name=\"collection\x5BdefaultValidation\x5D\"]").click(function () {
                    validator.settings.validation_groups = {"Default": true, "main": false};
                });
                form.find("*[name=\"collection\x5BmainValidation\x5D\"]").click(function () {
                    validator.settings.validation_groups = {"Default": false, "main": true};
                });
            })(jQuery);',
            $javascript
        );

        $elt = $client->getCrawler()->filterXPath('//div/@data-prototype-js');
        $javascriptPrototype = $elt->html();

        $this->assertEqualJs(
            '(function ($) {
                "use strict";
                var form = $("form[name=\"collection\"]");
                var validator = form.validate();
                form.find("*[name=\"collection\x5Btags\x5D\x5Btag__name__\x5D\"]").rules("add", {
                    "required": {
                        depends: function () {
                            return (validator.settings.validation_groups["Default"]);
                        }
                    },
                    "minlength": {
                        param: 8,
                        depends: function () {
                            return (validator.settings.validation_groups["main"]);
                        }
                    },
                    "maxlength": {
                        param: 200,
                        depends: function () {
                            return (validator.settings.validation_groups["main"]);
                        }
                    },
                    "messages": {
                        "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                        "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x208\x20characters\x20or\x20more.",
                        "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x20200\x20characters\x20or\x20less."
                    }
                });
            })(jQuery);',
            $javascriptPrototype
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
                "use strict";
                var form = $("form[name=\"collection\"]");
                var validator = form.validate({rules: {"collection\x5Btitle\x5D": {"required": true, "minlength": 8, "maxlength": 200}},
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
                "use strict";
                var form = $("form[name=\"collection\"]");
                var validator = form.validate();
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
                "use strict";
                var form = $("form[name=\"collection_compound\"]");
                var validator = form.validate({rules: {"collection_compound\x5Btitle\x5D": {"required": true, "minlength": 8, "maxlength": 200}},
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
                "use strict";
                var form = $("form[name=\"collection_compound\"]");
                var validator = form.validate();
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
                    "equalTo": {
                        param: "form[name=\"collection_compound\"] *[name=\"collection_compound[tags][__name__][password][first]\"]",
                        depends: function () {
                            if (("collection_compound\x5Btags\x5D\x5B__name__\x5D\x5Bpassword\x5D\x5Bfirst\x5D" in validator.errorMap || "collection_compound\x5Btags\x5D\x5B__name__\x5D\x5Bpassword\x5D\x5Bfirst\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    },
                    "messages": {"equalTo": "WRONG\x21"}
                });
            })(jQuery);',
            $javascriptPrototype
        );
    }

    /**
     * @test
     */
    public function it_should_render_collection_datetime_row_javascript()
    {
        $client = self::createClient();

        $javascript = $this->fetch_application_page_javascript('/collection_datetime', $client);

        $this->assertEqualJs(
            '(function ($) {
                "use strict";
                var form = $("form[name=\"collection_date_time\"]");
                var validator = form.validate({rules: {}, messages: {}});
                validator.settings.validation_groups = {"Default": false, "main": false};
                form.find("*[name=\"collection_date_time\x5BdefaultValidation\x5D\"]").click(function () {
                    validator.settings.validation_groups = {"Default": true, "main": false};
                });
                form.find("*[name=\"collection_date_time\x5BmainValidation\x5D\"]").click(function () {
                    validator.settings.validation_groups = {"Default": false, "main": true};
                });
            })(jQuery);',
            $javascript
        );

        $elt = $client->getCrawler()->filterXPath('//div/@data-prototype-js');
        $javascriptPrototype = $elt->text();

        $this->assertEqualJs(
            '(function ($) {
                "use strict";
                var form = $("form[name=\"collection_date_time\"]");
                var validator = form.validate();
                form.find("*[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D\"]").rules("add", {
                    "number": true,
                    "required": {
                        depends: function () {
                            return (validator.settings.validation_groups["Default"]);
                        }
                    },
                    "messages": {
                        "number": "This\x20value\x20is\x20not\x20valid.",
                        "required": "This\x20value\x20should\x20not\x20be\x20blank."
                    }
                });
                form.find("*[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D\"]").rules("add", {
                    "required": {
                        depends: function () {
                            var dep = form.find("[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D\"]")[0];
                            if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    },
                    "min": {
                        param: 1, depends: function () {
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    },
                    "max": {
                        param: 12, depends: function () {
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    },
                    "messages": {
                        "required": "This\x20value\x20is\x20not\x20valid.",
                        "min": "This\x20value\x20is\x20not\x20valid.",
                        "max": "This\x20value\x20is\x20not\x20valid."
                    }
                });
                form.find("*[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bday\x5D\"]").rules("add", {
                    "required": {
                        depends: function () {
                            var dep = form.find("[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D\"]")[0];
                            if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                return false;
                            }
                            var dep = form.find("[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D\"]")[0];
                            if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    },
                    "min": {
                        param: 1, depends: function () {
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                return false;
                            }
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    },
                    "max": {
                        param: 31, depends: function () {
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                return false;
                            }
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    },
                    "messages": {
                        "required": "This\x20value\x20is\x20not\x20valid.",
                        "min": "This\x20value\x20is\x20not\x20valid.",
                        "max": "This\x20value\x20is\x20not\x20valid."
                    }
                });
                form.find("*[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bhour\x5D\"]").rules("add", {
                    "min": 0,
                    "max": 23,
                    "required": {
                        depends: function () {
                            if (!(validator.settings.validation_groups["Default"])) {
                                return false;
                            }
                            var dep = form.find("[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D\"]")[0];
                            if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                return false;
                            }
                            var dep = form.find("[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D\"]")[0];
                            if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                return false;
                            }
                            var dep = form.find("[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bday\x5D\"]")[0];
                            if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bday\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bday\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    },
                    "messages": {
                        "min": "This\x20value\x20is\x20not\x20valid.",
                        "max": "This\x20value\x20is\x20not\x20valid.",
                        "required": "This\x20value\x20is\x20not\x20valid."
                    }
                });
                form.find("*[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bminute\x5D\"]").rules("add", {
                    "required": {
                        depends: function () {
                            var dep = form.find("[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D\"]")[0];
                            if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                return false;
                            }
                            var dep = form.find("[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D\"]")[0];
                            if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                return false;
                            }
                            var dep = form.find("[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bday\x5D\"]")[0];
                            if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bday\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bday\x5D" in validator.invalid)) {
                                return false;
                            }
                            var dep = form.find("[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bhour\x5D\"]")[0];
                            if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    },
                    "min": {
                        param: 0, depends: function () {
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    },
                    "max": {
                        param: 59, depends: function () {
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    },
                    "messages": {
                        "required": "This\x20value\x20is\x20not\x20valid.",
                        "min": "This\x20value\x20is\x20not\x20valid.",
                        "max": "This\x20value\x20is\x20not\x20valid."
                    }
                });
            })(jQuery);',
            $javascriptPrototype
        );
    }

    /**
     * @test
     */
    public function it_should_render_child_validation_javascript()
    {
        $client = self::createClient();

        $javascript = $this->fetch_application_page_javascript('/child_data', $client);

        $this->assertEqualJs(
            '(function ($) {
                "use strict";
                var form = $("form[name=\"root_form\"]");
                var validator = form.validate({
                    rules: {
                        "root_form\x5Broot\x5D": {"email": true},
                        "root_form\x5Bchild\x5D\x5Bname\x5D": {"required": true, "minlength": "2", "maxlength": "255"},
                        "root_form\x5Bchild\x5D\x5Bpassword\x5D\x5Bfirst\x5D": {"required": true, "minlength": "2", "maxlength": "255"},
                        "root_form\x5Bchild\x5D\x5Bpassword\x5D\x5Bsecond\x5D": {
                            "equalTo": {
                                param: "form[name=\"root_form\"] *[name=\"root_form[child][password][first]\"]",
                                depends: function () {
                                    if (("root_form\x5Bchild\x5D\x5Bpassword\x5D\x5Bfirst\x5D" in validator.errorMap || "root_form\x5Bchild\x5D\x5Bpassword\x5D\x5Bfirst\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        },
                        "root_form\x5BchildNoValidation\x5D\x5Bpassword\x5D\x5Bsecond\x5D": {
                            "equalTo": {
                                param: "form[name=\"root_form\"] *[name=\"root_form[childNoValidation][password][first]\"]",
                                depends: function () {
                                    if (("root_form\x5BchildNoValidation\x5D\x5Bpassword\x5D\x5Bfirst\x5D" in validator.errorMap || "root_form\x5BchildNoValidation\x5D\x5Bpassword\x5D\x5Bfirst\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        }
                    },
                    messages: {
                        "root_form\x5Broot\x5D": {"email": "This\x20value\x20is\x20not\x20a\x20valid\x20email\x20address."},
                        "root_form\x5Bchild\x5D\x5Bname\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x202\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x20255\x20characters\x20or\x20less."
                        },
                        "root_form\x5Bchild\x5D\x5Bpassword\x5D\x5Bfirst\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x202\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x20255\x20characters\x20or\x20less."
                        },
                        "root_form\x5Bchild\x5D\x5Bpassword\x5D\x5Bsecond\x5D": {"equalTo": "This\x20value\x20is\x20not\x20valid."},
                        "root_form\x5BchildNoValidation\x5D\x5Bpassword\x5D\x5Bsecond\x5D": {"equalTo": "This\x20value\x20is\x20not\x20valid."}
                    }
                });
            })(jQuery);',

            $javascript
        );
    }

    /**
     * @test
     */
    public function it_should_validate_sub_forms_witch_are_not_mapped()
    {
        $client = self::createClient();

        $javascript = $this->fetch_application_page_javascript('/include_simple_data', $client);

        $this->assertEqualJs(
            '(function ($) {
                "use strict";
                var form = $("form[name=\"include_simple_form_data\"]");
                var validator = form.validate({
                    rules: {
                        "include_simple_form_data\x5Buser\x5D\x5Bname\x5D": {
                            "required": true,
                            "minlength": "2",
                            "maxlength": "255"
                        },
                        "include_simple_form_data\x5Buser\x5D\x5Bpassword\x5D\x5Bfirst\x5D": {
                            "required": true,
                            "minlength": "2",
                            "maxlength": "255"
                        },
                        "include_simple_form_data\x5Buser\x5D\x5Bpassword\x5D\x5Bsecond\x5D": {
                            "equalTo": {
                                param: "form[name=\"include_simple_form_data\"] *[name=\"include_simple_form_data[user][password][first]\"]",
                                depends: function () {
                                    if (("include_simple_form_data\x5Buser\x5D\x5Bpassword\x5D\x5Bfirst\x5D" in validator.errorMap || "include_simple_form_data\x5Buser\x5D\x5Bpassword\x5D\x5Bfirst\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        }
                    },
                    messages: {
                        "include_simple_form_data\x5Buser\x5D\x5Bname\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x202\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x20255\x20characters\x20or\x20less."
                        },
                        "include_simple_form_data\x5Buser\x5D\x5Bpassword\x5D\x5Bfirst\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x202\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x20255\x20characters\x20or\x20less."
                        },
                        "include_simple_form_data\x5Buser\x5D\x5Bpassword\x5D\x5Bsecond\x5D": {"equalTo": "This\x20value\x20is\x20not\x20valid."}
                    }
                });
            })(jQuery);',
            $javascript
        );
    }

    /**
     * @test
     */
    public function it_should_render_date_time_javascript()
    {
        $client = self::createClient();

        $javascript = $this->fetch_application_page_javascript('/date_time', $client);

        $this->assertEqualJs(
            '(function ($) {
                "use strict";
                var form = $("form[name=\"date_time_form\"]");
                var validator = form.validate({
                    rules: {
                        "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D": {
                            "number": true,
                            "required": true
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "min": {
                                param: 1, depends: function () {
                                    if (("date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "max": {
                                param: 12, depends: function () {
                                    if (("date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    var dep = form.find("[name=\"date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "min": {
                                param: 1, depends: function () {
                                    if (("date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    if (("date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "max": {
                                param: 31, depends: function () {
                                    if (("date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    if (("date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D": {
                            "min": 0,
                            "max": 23,
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    var dep = form.find("[name=\"date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    var dep = form.find("[name=\"date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bminute\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    var dep = form.find("[name=\"date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    var dep = form.find("[name=\"date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    var dep = form.find("[name=\"date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "min": {
                                param: 0, depends: function () {
                                    if (("date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "max": {
                                param: 59, depends: function () {
                                    if (("date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        },
                        "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D": {"number": true, "required": true},
                        "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdate_choice\x5D\x5Byear\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "min": {
                                param: 1, depends: function () {
                                    if (("date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "max": {
                                param: 12, depends: function () {
                                    if (("date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        },
                        "date_time_form\x5Bdate_choice\x5D\x5Bday\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdate_choice\x5D\x5Byear\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    var dep = form.find("[name=\"date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "min": {
                                param: 1, depends: function () {
                                    if (("date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    if (("date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "max": {
                                param: 31, depends: function () {
                                    if (("date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    if (("date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        },
                        "date_time_form\x5Bdate_text\x5D\x5Byear\x5D": {"number": true, "required": true},
                        "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdate_text\x5D\x5Byear\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "min": {
                                param: 1, depends: function () {
                                    if (("date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "max": {
                                param: 12, depends: function () {
                                    if (("date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        },
                        "date_time_form\x5Bdate_text\x5D\x5Bday\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdate_text\x5D\x5Byear\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    var dep = form.find("[name=\"date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "min": {
                                param: 1, depends: function () {
                                    if (("date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    if (("date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "max": {
                                param: 31, depends: function () {
                                    if (("date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    if (("date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        },
                        "date_time_form\x5Bdate_single_text\x5D": {"required": true},
                        "date_time_form\x5Btime_choice\x5D\x5Bhour\x5D": {"min": 0, "max": 23, "required": true},
                        "date_time_form\x5Btime_choice\x5D\x5Bminute\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Btime_choice\x5D\x5Bhour\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Btime_choice\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Btime_choice\x5D\x5Bhour\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "min": {
                                param: 0, depends: function () {
                                    if (("date_time_form\x5Btime_choice\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Btime_choice\x5D\x5Bhour\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "max": {
                                param: 59, depends: function () {
                                    if (("date_time_form\x5Btime_choice\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Btime_choice\x5D\x5Bhour\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        },
                        "date_time_form\x5Btime_text\x5D\x5Bhour\x5D": {"min": 0, "max": 23, "required": true},
                        "date_time_form\x5Btime_text\x5D\x5Bminute\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Btime_text\x5D\x5Bhour\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "date_time_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "min": {
                                param: 0, depends: function () {
                                    if (("date_time_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "max": {
                                param: 59, depends: function () {
                                    if (("date_time_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        },
                        "date_time_form\x5Btime_single_text\x5D": {"required": true}
                    },
                    messages: {
                        "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D": {
                            "number": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D": {
                            "required": "This\x20value\x20is\x20not\x20valid.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D": {
                            "required": "This\x20value\x20is\x20not\x20valid.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bminute\x5D": {
                            "required": "This\x20value\x20is\x20not\x20valid.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D": {
                            "number": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D": {
                            "required": "This\x20value\x20is\x20not\x20valid.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdate_choice\x5D\x5Bday\x5D": {
                            "required": "This\x20value\x20is\x20not\x20valid.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdate_text\x5D\x5Byear\x5D": {
                            "number": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D": {
                            "required": "This\x20value\x20is\x20not\x20valid.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdate_text\x5D\x5Bday\x5D": {
                            "required": "This\x20value\x20is\x20not\x20valid.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdate_single_text\x5D": {"required": "This\x20value\x20should\x20not\x20be\x20blank."},
                        "date_time_form\x5Btime_choice\x5D\x5Bhour\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Btime_choice\x5D\x5Bminute\x5D": {
                            "required": "This\x20value\x20is\x20not\x20valid.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Btime_text\x5D\x5Bhour\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Btime_text\x5D\x5Bminute\x5D": {
                            "required": "This\x20value\x20is\x20not\x20valid.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Btime_single_text\x5D": {"required": "This\x20value\x20should\x20not\x20be\x20blank."}
                    }
                });
            })(jQuery);',
            $javascript
        );
    }

    /**
     * @test
     */
    public function it_should_not_render_groups_for_view_transformer_rules_javascript()
    {
        $client = self::createClient();

        $javascript = $this->fetch_application_page_javascript('/view_transform', $client);

        $this->assertEqualJs(
            '(function ($) {
                "use strict";
                var form = $("form[name=\"view_transform_rules_form\"]");
                var validator = form.validate({
                    rules: {
                        "view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D": {
                            "min": 0,
                            "max": 23,
                            "required": {
                                depends: function () {
                                    return (validator.settings.validation_groups["Default"]);
                                }
                            }
                        },
                        "view_transform_rules_form\x5Btime_text\x5D\x5Bminute\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D\"]")[0];
                                    if ((!$.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) || "view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.errorMap || "view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            },
                            "min": {
                                param: 0, depends: function () {
                                    if (("view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.errorMap || "view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }, "max": {
                                param: 59, depends: function () {
                                    if (("view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.errorMap || "view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        },
                        "view_transform_rules_form\x5Bequals\x5D\x5Bfirst\x5D": {
                            "required": {
                                depends: function () {
                                    return (validator.settings.validation_groups["main"]);
                                }
                            }
                        },
                        "view_transform_rules_form\x5Bequals\x5D\x5Bsecond\x5D": {
                            "equalTo": {
                                param: "form[name=\"view_transform_rules_form\"] *[name=\"view_transform_rules_form[equals][first]\"]",
                                depends: function () {
                                    if (("view_transform_rules_form\x5Bequals\x5D\x5Bfirst\x5D" in validator.errorMap || "view_transform_rules_form\x5Bequals\x5D\x5Bfirst\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        }
                    },
                    messages: {
                        "view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "view_transform_rules_form\x5Btime_text\x5D\x5Bminute\x5D": {
                            "required": "This\x20value\x20is\x20not\x20valid.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "view_transform_rules_form\x5Bequals\x5D\x5Bfirst\x5D": {"required": "This\x20value\x20should\x20not\x20be\x20blank."},
                        "view_transform_rules_form\x5Bequals\x5D\x5Bsecond\x5D": {"equalTo": "Oops\x20they\x20don\x27t\x20match"}
                    }
                });
                validator.settings.validation_groups = {"Default": false, "main": false};
                form.find("*[name=\"view_transform_rules_form\x5BdefaultValidation\x5D\"]").click(function () {
                    validator.settings.validation_groups = {"Default": true, "main": false};
                });
                form.find("*[name=\"view_transform_rules_form\x5BmainValidation\x5D\"]").click(function () {
                    validator.settings.validation_groups = {"Default": false, "main": true};
                });
            })(jQuery);',
            $javascript
        );
    }

    /**
     * @test
     */
    public function it_should_not_render_additional_rules()
    {
        $client = self::createClient();

        $javascript = $this->fetch_application_page_javascript('/additional_rules', $client);

        $this->assertEqualJs('
            (function ($) {
                "use strict";
                var form = $("form[name=\"additional_rules\"]");
                var validator = form.validate({ rules: {}, messages: {}});
            })(jQuery);',
            $javascript
        );
    }

    /**
     * @test
     */
    public function it_should_render_additional_groups_when_specified()
    {
        $client = self::createClient();

        $javascript = $this->fetch_application_page_javascript('/manual_groups', $client);

        $this->assertEqualJs('
            (function ($) {
                "use strict";
                var form = $("form[name=\"manualGroups\"]");
                var validator = form.validate({
                    rules: {
                        "manualGroups\x5Bname\x5D": {
                            "required": {
                                depends: function () {
                                    return (validator.settings.validation_groups["Default"]);
                                }
                            }, "minlength": {
                                param: "2", depends: function () {
                                    return (validator.settings.validation_groups["lengthGroup"]);
                                }
                            }, "maxlength": {
                                param: "10", depends: function () {
                                    return (validator.settings.validation_groups["lengthGroup"]);
                                }
                            }
                        }
                    }, messages: {
                        "manualGroups\x5Bname\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x202\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x2010\x20characters\x20or\x20less."
                        }
                    }
                });
                validator.settings.validation_groups = {"Default": true, "lengthGroup": true};
            })(jQuery);',
            $javascript
        );
    }

    /**
     * @test
     */
    public function issue_7_should_not_occur()
    {
        $client = self::createClient();

        $javascript = $this->fetch_application_page_javascript('/issue/7', $client);

        $this->assertEqualJs(
            '(function ($) {
                "use strict";
                var form = $("form[name=\"recourses\"]");
                var validator = form.validate({
                    rules: {
                        "recourses\x5Bcontents\x5D\x5B0\x5D\x5Bmessage\x5D": {
                            "required": true,
                            "minlength": 3,
                            "maxlength": 8196
                        },
                        "recourses\x5Bcontents\x5D\x5B1\x5D\x5Bmessage\x5D": {
                            "required": true,
                            "minlength": 3,
                            "maxlength": 8196
                        }
                    },
                    messages: {
                        "recourses\x5Bcontents\x5D\x5B0\x5D\x5Bmessage\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x203\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x208196\x20characters\x20or\x20less."
                        },
                        "recourses\x5Bcontents\x5D\x5B1\x5D\x5Bmessage\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x203\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x208196\x20characters\x20or\x20less."
                        }
                    }
                });
            })(jQuery);',
            $javascript
        );
    }

    /**
     * @test
     */
    public function issue_8_should_not_occur()
    {
        $client = self::createClient();

        $javascript = $this->fetch_application_page_javascript('/issue/8', $client);

        $this->assertEqualJs(
            '(function ($) {
                "use strict";
                var form = $("form[name=\"test_range\"]");
                var validator = form.validate({
                    rules: {
                        "test_range\x5Bsize\x5D\x5Bmin_size\x5D": {"required": true, "min": 400},
                        "test_range\x5Bsize\x5D\x5Bmax_size\x5D": {"required": true, "max": 2000}
                    },
                    messages: {
                        "test_range\x5Bsize\x5D\x5Bmin_size\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "min": "This\x20value\x20should\x20be\x20greater\x20than\x20or\x20equal\x20to\x20400."
                        },
                        "test_range\x5Bsize\x5D\x5Bmax_size\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "max": "This\x20value\x20should\x20be\x20less\x20than\x20or\x20equal\x20to\x202000."
                        }
                    }
                });
            })(jQuery);',
            $javascript
        );
    }

    /**
     * @test
     */
    public function issue_16_property_path_hell()
    {
        $javascript = $this->fetch_application_page_javascript('/issue/16');
        $this->assertEqualJs(
            '(function ($) {
                "use strict";
                var form = $("form[name=\"issue16_collection\"]");
                var validator = form.validate({
                    rules: {
                        "issue16_collection\x5Brooot\x5D": {
                            "required": true,
                            "minlength": "2",
                            "maxlength": "255"
                        },
                        "issue16_collection\x5BentityReferences\x5D\x5B0\x5D\x5Breference\x5D": {
                            "required": true,
                            "minlength": "2",
                            "maxlength": "255"
                        }
                    },
                    messages: {
                        "issue16_collection\x5Brooot\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x202\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x20255\x20characters\x20or\x20less."
                        },
                        "issue16_collection\x5BentityReferences\x5D\x5B0\x5D\x5Breference\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x202\x20characters\x20or\x20more.",
                            "maxlength": "This\x20value\x20is\x20too\x20long.\x20It\x20should\x20have\x20255\x20characters\x20or\x20less."
                        }
                    }
                });
            })(jQuery);',
            $javascript
        );
    }

    /**
     * @test
     */
    public function issue_17_it_should_render_form_javascript_with_a_empty_name()
    {
        $javascript = $this->fetch_application_page_javascript('/issue/17');
        $this->assertEqualJs(
            '(function ($) {
                "use strict";
                var form = $("form[name=\"\"]");
                var validator = form.validate({
                    rules: {
                        "name": {"required": true, "minlength": 2},
                        "password\x5Bfirst\x5D": {"required": true},
                        "password\x5Bsecond\x5D": {
                            "equalTo": {
                                param: "form[name=\"\"] *[name=\"password[first]\"]",
                                depends: function () {
                                    if (("password\x5Bfirst\x5D" in validator.errorMap || "password\x5Bfirst\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            }
                        }
                    },
                    messages: {
                        "name": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "minlength": "This\x20value\x20is\x20too\x20short.\x20It\x20should\x20have\x202\x20characters\x20or\x20more."
                        },
                        "password\x5Bfirst\x5D": {"required": "This\x20value\x20should\x20not\x20be\x20blank."},
                        "password\x5Bsecond\x5D": {"equalTo": "WRONG\x21"}
                    }
                });
            })(jQuery);',
            $javascript
        );
    }

    /**
     * @test
     */
    public function issue_18_avoid_null_validation_group_exception()
    {
        $javascript = $this->fetch_application_page_javascript('/issue/18');
        $this->assertEqualJs(
            '(function ($) {
                "use strict";
                var form = $("form[name=\"\"]");
                var validator = form.validate({
                    rules: { },
                    messages: { }
                });
            })(jQuery);',
            $javascript
        );
    }
}
