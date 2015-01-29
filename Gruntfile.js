module.exports = function (grunt) {
    "use strict";

    var banner = "/*!\n" +
        " * jQueryValidationBundle Plugin\n" +
        " *\n" +
        " * <%= pkg.homepage %>\n" +
        " *\n" +
        " * Copyright (c) <%= grunt.template.today('yyyy') %> <%= pkg.author.name %>\n" +
        " * Released under the <%= _.pluck(pkg.licenses, 'type').join(', ') %> license\n" +
        " */\n";

    // define UMD wrapper variables
    var umdStart = "(function( factory ) {\n" +
    "\tif ( typeof define === \"function\" && define.amd ) {\n";

    var umdMiddle = "\t} else {\n" +
    "\t\tfactory( jQuery );\n" +
    "\t}\n" +
    "}(function( $ ) {\n\n";

    var umdEnd = "\n}));";
    var umdAdditionalDefine = "\t\tdefine( [\"jquery\", \"jquery.validate\"], factory );\n";

    grunt.initConfig({
        pkg: grunt.file.readJSON("package.json"),
        jshint: {
            all: ['src/Resources/public/additional/*.js', 'tests/Javascript/*.js']
        },
        qunit: {
            all: ['tests/Javascript/index.html']
        },
        concat: {
            additional: {
                options: {
                    banner: banner +
                    umdStart +
                    umdAdditionalDefine +
                    umdMiddle,
                    footer: umdEnd
                },
                files: {
                    "src/Resources/public/additional-methods.js": [
                        "node_modules/jquery-validation/src/additional/accept.js",
                        "node_modules/jquery-validation/src/additional/iban.js",
                        "node_modules/jquery-validation/src/additional/ipv4.js",
                        "node_modules/jquery-validation/src/additional/ipv6.js",
                        "node_modules/jquery-validation/src/additional/pattern.js",
                        "node_modules/jquery-validation/src/additional/time.js",
                        "src/Resources/public/additional/*.js"
                    ]
                }
            }
        },
        uglify: {
            options: {
                preserveComments: false,
                banner: "/*! <%= pkg.title || pkg.name %>\n" +
                " * <%= pkg.homepage %>\n" +
                " * Copyright (c) <%= grunt.template.today('yyyy') %> <%= pkg.author.name %>;" +
                " Licensed <%= _.pluck(pkg.licenses, 'type').join(', ') %> */\n"
            },
            additional: {
                files: {
                    "src/Resources/public/additional-methods.min.js": "src/Resources/public/additional-methods.js",
                    "tests/Functional/web/js/additional-methods.js": "src/Resources/public/additional-methods.js"
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-qunit');
    grunt.loadNpmTasks("grunt-contrib-concat");
    grunt.loadNpmTasks("grunt-contrib-uglify");

    grunt.registerTask('build', ['concat', 'uglify']);
    grunt.registerTask('test', ['jshint', 'qunit']);
    grunt.registerTask('default', ['build', 'test']);
};