<?php
namespace Tests\Boekkooi\Bundle\JqueryValidationBundle\Functional;

/**
 * @coversNothing
 * @runTestsInSeparateProcesses
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class TotalFormTest extends FormTestCase
{
    protected static function getSymfonyOptions()
    {
        return array(
            'enable_additionals' => true,
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
                    "required_group": ["collection_date_time[tags][__name__][date][month]", "collection_date_time[tags][__name__][date][day]", "collection_date_time[tags][__name__][time][hour]", "collection_date_time[tags][__name__][time][minute]"],
                    "required": {
                        depends: function () {
                            return (validator.settings.validation_groups["Default"]);
                        }
                    },
                    "messages": {
                        "number": "This\x20value\x20is\x20not\x20valid.",
                        "required_group": "This\x20value\x20is\x20not\x20valid.",
                        "required": "This\x20value\x20should\x20not\x20be\x20blank."
                    }
                });
                form.find("*[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D\"]").rules("add", {
                    "min": {
                        param: 1,
                        depends: function () {
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    }, "max": {
                        param: 12, depends: function () {
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    }, "messages": {"min": "This\x20value\x20is\x20not\x20valid.", "max": "This\x20value\x20is\x20not\x20valid."}
                });
                form.find("*[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bday\x5D\"]").rules("add", {
                    "min": {
                        param: 1,
                        depends: function () {
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                return false;
                            }
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    }, "max": {
                        param: 31, depends: function () {
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Byear\x5D" in validator.invalid)) {
                                return false;
                            }
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Bdate\x5D\x5Bmonth\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    }, "messages": {"min": "This\x20value\x20is\x20not\x20valid.", "max": "This\x20value\x20is\x20not\x20valid."}
                });
                form.find("*[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bhour\x5D\"]").rules("add", {
                    "min": 0,
                    "max": 23,
                    "required_group": ["collection_date_time[tags][__name__][time][minute]"],
                    "messages": {
                        "min": "This\x20value\x20is\x20not\x20valid.",
                        "max": "This\x20value\x20is\x20not\x20valid.",
                        "required_group": "This\x20value\x20is\x20not\x20valid."
                    }
                });
                form.find("*[name=\"collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bminute\x5D\"]").rules("add", {
                    "min": {
                        param: 0,
                        depends: function () {
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    }, "max": {
                        param: 59, depends: function () {
                            if (("collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.errorMap || "collection_date_time\x5Btags\x5D\x5B__name__\x5D\x5Btime\x5D\x5Bhour\x5D" in validator.invalid)) {
                                return false;
                            }
                            return true;
                        }
                    }, "messages": {"min": "This\x20value\x20is\x20not\x20valid.", "max": "This\x20value\x20is\x20not\x20valid."}
                });
            })(jQuery);',
            $javascriptPrototype
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
                            "required_group": ["date_time_form[datetime_choice][date][month]", "date_time_form[datetime_choice][date][day]", "date_time_form[datetime_choice][time][hour]", "date_time_form[datetime_choice][time][minute]"],
                            "required": true
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D": {
                            "min": {
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
                            "min": {
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
                            "required_group": ["date_time_form[datetime_choice][time][minute]"]
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bminute\x5D": {
                            "min": {
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
                        "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D": {
                            "number": true,
                            "required_group": ["date_time_form[date_choice][month]", "date_time_form[date_choice][day]"],
                            "required": true
                        },
                        "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D": {
                            "min": {
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
                            "min": {
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
                        "date_time_form\x5Bdate_text\x5D\x5Byear\x5D": {
                            "number": true,
                            "required_group": ["date_time_form[date_text][month]", "date_time_form[date_text][day]"],
                            "required": true
                        },
                        "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D": {
                            "min": {
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
                            "min": {
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
                        "date_time_form\x5Btime_choice\x5D\x5Bhour\x5D": {
                            "min": 0,
                            "max": 23,
                            "required_group": ["date_time_form[time_choice][minute]"],
                            "required": true
                        },
                        "date_time_form\x5Btime_choice\x5D\x5Bminute\x5D": {
                            "min": {
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
                        "date_time_form\x5Btime_text\x5D\x5Bhour\x5D": {
                            "min": 0,
                            "max": 23,
                            "required_group": ["date_time_form[time_text][minute]"],
                            "required": true
                        },
                        "date_time_form\x5Btime_text\x5D\x5Bminute\x5D": {
                            "min": {
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
                        "date_time_form\x5Btime_single_text\x5D": {"required": true, "time": true}
                    },
                    messages: {
                        "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Byear\x5D": {
                            "number": "This\x20value\x20is\x20not\x20valid.",
                            "required_group": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bmonth\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Bdate\x5D\x5Bday\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bhour\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid.",
                            "required_group": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdatetime_choice\x5D\x5Btime\x5D\x5Bminute\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdate_choice\x5D\x5Byear\x5D": {
                            "number": "This\x20value\x20is\x20not\x20valid.",
                            "required_group": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Bdate_choice\x5D\x5Bmonth\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdate_choice\x5D\x5Bday\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdate_text\x5D\x5Byear\x5D": {
                            "number": "This\x20value\x20is\x20not\x20valid.",
                            "required_group": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Bdate_text\x5D\x5Bmonth\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdate_text\x5D\x5Bday\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Bdate_single_text\x5D": {"required": "This\x20value\x20should\x20not\x20be\x20blank."},
                        "date_time_form\x5Btime_choice\x5D\x5Bhour\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid.",
                            "required_group": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Btime_choice\x5D\x5Bminute\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Btime_text\x5D\x5Bhour\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid.",
                            "required_group": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "date_time_form\x5Btime_text\x5D\x5Bminute\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid."
                        },
                        "date_time_form\x5Btime_single_text\x5D": {
                            "required": "This\x20value\x20should\x20not\x20be\x20blank.",
                            "time": "This\x20value\x20is\x20not\x20valid."
                        }
                    }
                });
            })(jQuery);
',
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
                            "required_group": ["view_transform_rules_form[time_text][minute]"],
                            "required": {
                                depends: function () {
                                    return (validator.settings.validation_groups["Default"]);
                                }
                            }
                        },
                        "view_transform_rules_form\x5Btime_text\x5D\x5Bminute\x5D": {
                            "min": {
                                param: 0, depends: function () {
                                    if (("view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.errorMap || "view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D" in validator.invalid)) {
                                        return false;
                                    }
                                    return true;
                                }
                            },
                            "max": {
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
                        "view_transform_rules_form\x5Bequals\x5D\x5Bsecond\x5D": {"equalTo": "form[name=\"view_transform_rules_form\"] *[name=\"view_transform_rules_form[equals][first]\"]"}
                    },
                    messages: {
                        "view_transform_rules_form\x5Btime_text\x5D\x5Bhour\x5D": {
                            "min": "This\x20value\x20is\x20not\x20valid.",
                            "max": "This\x20value\x20is\x20not\x20valid.",
                            "required_group": "This\x20value\x20is\x20not\x20valid.",
                            "required": "This\x20value\x20should\x20not\x20be\x20blank."
                        },
                        "view_transform_rules_form\x5Btime_text\x5D\x5Bminute\x5D": {
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
    public function it_should_additional_rules()
    {
        $client = self::createClient();

        $javascript = $this->fetch_application_page_javascript('/additional_rules', $client);

        $this->assertEqualJs('
            (function ($) {
                "use strict";
                var form = $("form[name=\"additional_rules\"]");
                var validator = form.validate({
                    rules: {
                        "additional_rules\x5Bipv4\x5D": {"ipv4": true},
                        "additional_rules\x5Bipv6\x5D": {"ipv6": true},
                        "additional_rules\x5Bipv4_ipv6\x5D": {"one_or_other": {"ipv4": true, "ipv6": true}},
                        "additional_rules\x5Biban\x5D": {"iban": true},
                        "additional_rules\x5Bluhn\x5D": {"luhn": true},
                        "additional_rules\x5Bfile\x5D": {"accept": "text\/plain,application\/pdf"},
                        "additional_rules\x5Bpattern\x5D": {"pattern": "[a-zA-Z]+"}
                    },
                    messages: {
                        "additional_rules\x5Bipv4\x5D": {"ipv4": "This\x20is\x20not\x20a\x20valid\x20IP\x20address."},
                        "additional_rules\x5Bipv6\x5D": {"ipv6": "This\x20is\x20not\x20a\x20valid\x20IP\x20address."},
                        "additional_rules\x5Bipv4_ipv6\x5D": {"one_or_other": "This\x20is\x20not\x20a\x20valid\x20IP\x20address."},
                        "additional_rules\x5Biban\x5D": {"iban": "This\x20is\x20not\x20a\x20valid\x20International\x20Bank\x20Account\x20Number\x20\x28IBAN\x29."},
                        "additional_rules\x5Bluhn\x5D": {"luhn": "Invalid\x20card\x20number."},
                        "additional_rules\x5Bfile\x5D": {"accept": "The\x20mime\x20type\x20of\x20the\x20file\x20is\x20invalid\x20\x28\x7B\x7B\x20type\x20\x7D\x7D\x29.\x20Allowed\x20mime\x20types\x20are\x20text\x2Fplain,\x20application\x2Fpdf."},
                        "additional_rules\x5Bpattern\x5D": {"pattern": "This\x20value\x20is\x20not\x20valid."}
                    }
                });
            })(jQuery);',
            $javascript
        );
    }
}
