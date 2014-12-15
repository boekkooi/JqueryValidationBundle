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
                "use strict";
                var form = $("form[name=\"simple_form\"]");
                var validator = form.validate({
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
                "use strict";
                var form = $("form[name=\"simple_form_data\"]");
                var validator = form.validate({
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
                    "equalTo": "form[name=\"collection_compound\"] *[name=\"collection_compound[tags][__name__][password][first]\"]",
                    "messages": {"equalTo": "WRONG\x21"}
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
                        "root_form\x5Bchild\x5D\x5Bpassword\x5D\x5Bsecond\x5D": {"equalTo": "form[name=\"root_form\"] *[name=\"root_form[child][password][first]\"]"},
                        "root_form\x5BchildNoValidation\x5D\x5Bpassword\x5D\x5Bsecond\x5D": {"equalTo": "form[name=\"root_form\"] *[name=\"root_form[childNoValidation][password][first]\"]"}
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
                        "include_simple_form_data\x5Buser\x5D\x5Bpassword\x5D\x5Bsecond\x5D": {"equalTo": "form[name=\"include_simple_form_data\"] *[name=\"include_simple_form_data[user][password][first]\"]"}
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
                                    return $.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) && !("date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid);
                                }
                            },
                            "min": {
                                param: 1, depends: function () {
                                    return !("date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid);
                                }
                            },
                            "max": {
                                param: 12, depends: function () {
                                    return !("date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid);
                                }
                            }
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D\"]")[0];
                                    return $.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) && !("date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid);
                                }
                            },
                            "min": {
                                param: 1, depends: function () {
                                    return !("date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid);
                                }
                            },
                            "max": {
                                param: 31, depends: function () {
                                    return !("date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid);
                                }
                            }
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D": {
                            "min": 0,
                            "max": 23,
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D\"]")[0];
                                    return $.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) && !("date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D" in validator.invalid);
                                }
                            }
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bminute\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D\"]")[0];
                                    return $.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) && !("date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D" in validator.invalid || "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.invalid);
                                }
                            },
                            "min": {
                                param: 0, depends: function () {
                                    return !("date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.invalid);
                                }
                            },
                            "max": {
                                param: 59, depends: function () {
                                    return !("date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.invalid);
                                }
                            }
                        },
                        "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D": {"number": true, "required": true},
                        "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdate_choice\x5D\x5Byear\x5D\"]")[0];
                                    return $.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) && !("date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.invalid);
                                }
                            },
                            "min": {
                                param: 1, depends: function () {
                                    return !("date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.invalid);
                                }
                            },
                            "max": {
                                param: 12, depends: function () {
                                    return !("date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.invalid);
                                }
                            }
                        },
                        "date_time_form\x5Bdate_choice\x5D\x5Bday\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D\"]")[0];
                                    return $.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) && !("date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.invalid || "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D" in validator.invalid);
                                }
                            },
                            "min": {
                                param: 1, depends: function () {
                                    return !("date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.invalid || "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D" in validator.invalid);
                                }
                            },
                            "max": {
                                param: 31, depends: function () {
                                    return !("date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D" in validator.invalid || "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D" in validator.invalid);
                                }
                            }
                        },
                        "date_time_form\x5Bdate_text\x5D\x5Byear\x5D": {"number": true, "required": true},
                        "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdate_text\x5D\x5Byear\x5D\"]")[0];
                                    return $.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) && !("date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.invalid);
                                }
                            },
                            "min": {
                                param: 1, depends: function () {
                                    return !("date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.invalid);
                                }
                            },
                            "max": {
                                param: 12, depends: function () {
                                    return !("date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.invalid);
                                }
                            }
                        },
                        "date_time_form\x5Bdate_text\x5D\x5Bday\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D\"]")[0];
                                    return $.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) && !("date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.invalid || "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D" in validator.invalid);
                                }
                            },
                            "min": {
                                param: 1, depends: function () {
                                    return !("date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.invalid || "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D" in validator.invalid);
                                }
                            },
                            "max": {
                                param: 31, depends: function () {
                                    return !("date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Byear\x5D" in validator.invalid || "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D" in validator.errorMap || "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D" in validator.invalid);
                                }
                            }
                        },
                        "date_time_form\x5Bdate_single_text\x5D": {"required": true},
                        "date_time_form\x5Btime_choice\x5D\x5Bhour\x5D": {"min": 0, "max": 23, "required": true},
                        "date_time_form\x5Btime_choice\x5D\x5Bminute\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Btime_choice\x5D\x5Bhour\x5D\"]")[0];
                                    return $.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) && !("date_time_form\x5Btime_choice\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Btime_choice\x5D\x5Bhour\x5D" in validator.invalid);
                                }
                            },
                            "min": {
                                param: 0, depends: function () {
                                    return !("date_time_form\x5Btime_choice\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Btime_choice\x5D\x5Bhour\x5D" in validator.invalid);
                                }
                            },
                            "max": {
                                param: 59, depends: function () {
                                    return !("date_time_form\x5Btime_choice\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Btime_choice\x5D\x5Bhour\x5D" in validator.invalid);
                                }
                            }
                        },
                        "date_time_form\x5Btime_text\x5D\x5Bhour\x5D": {"min": 0, "max": 23, "required": true},
                        "date_time_form\x5Btime_text\x5D\x5Bminute\x5D": {
                            "required": {
                                depends: function () {
                                    var dep = form.find("[name=\"date_time_form\x5Btime_text\x5D\x5Bhour\x5D\"]")[0];
                                    return $.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) && !("date_time_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.invalid);
                                }
                            },
                            "min": {
                                param: 0, depends: function () {
                                    return !("date_time_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.invalid);
                                }
                            },
                            "max": {
                                param: 59, depends: function () {
                                    return !("date_time_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.errorMap || "date_time_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.invalid);
                                }
                            }
                        },
                        "date_time_form\x5Btime_single_text\x5D": {"required": true, "time": true}
                    },
                    messages: {
                        "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D": {
                            "number": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bminute\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D": {
                            "number": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdate_choice\x5D\x5Bday\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdate_text\x5D\x5Byear\x5D": {
                            "number": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdate_text\x5D\x5Bday\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
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
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Btime_text\x5D\x5Bhour\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Btime_text\x5D\x5Bminute\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Btime_single_text\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "time": "This\x20value\x20is\x20not\x20valid."
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
                                    return $.validator.methods.required.call(validator, validator.elementValue(dep), dep, true) && !("view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.errorMap || "view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.invalid);
                                }
                            },
                            "min": {
                                param: 0, depends: function () {
                                    return !("view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.errorMap || "view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.invalid);
                                }
                            }, "max": {
                                param: 59, depends: function () {
                                    return !("view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.errorMap || "view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.invalid);
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
                        "view_transform_rules_form\x5Bequals\x5D\x5Bsecond\x5D": {"equalTo": "form[name=\"view_transform_rules_form\"] *[name=\"view_transform_rules_form[equals][first]\"]"}
                    },
                    messages: {
                        "view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "view_transform_rules_form\x5Btime_text\x5D\x5Bminute\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
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
        $this->assertEquals(
            $this->stripWhiteSpace($excepted),
            $this->stripWhiteSpace($actual)
        );
    }

    protected function stripWhiteSpace($js)
    {
        $js = str_replace(array(',', '{', '}', ':', ';', 'function'), array(', ', ' { ', ' } ', ' : ', '; ', ' function ') , trim($js));

        return preg_replace('/(\s+|\n)/', ' ' , trim($js));
    }
}
