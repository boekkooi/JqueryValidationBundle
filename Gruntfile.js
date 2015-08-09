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
        gitclone: {
            'validate': {
                options: {
                    repository: 'https://github.com/jzaefferer/jquery-validation.git',
                    directory: '.tmp/jquery-validate'
                }
            }
        },
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
                        ".tmp/jquery-validate/src/additional/accept.js",
                        ".tmp/jquery-validate/src/additional/iban.js",
                        ".tmp/jquery-validate/src/additional/ipv4.js",
                        ".tmp/jquery-validate/src/additional/ipv6.js",
                        ".tmp/jquery-validate/src/additional/pattern.js",
                        ".tmp/jquery-validate/src/additional/time.js",
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
    grunt.loadNpmTasks('grunt-git');

    grunt.registerTask('prepare', ['gitclone']);

    grunt.registerTask('build', ['concat', 'uglify']);
    grunt.registerTask('test', ['jshint', 'qunit']);

    grunt.registerTask('default', ['build', 'test']);
};
