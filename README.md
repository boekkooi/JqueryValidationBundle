Boekkooi Jquery Validation Bundle
=============
[![Build Status](https://travis-ci.org/boekkooi/JqueryValidationBundle.svg?branch=master)](https://travis-ci.org/boekkooi/JqueryValidationBundle)
[![Code Coverage](https://scrutinizer-ci.com/g/boekkooi/JqueryValidationBundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/boekkooi/JqueryValidationBundle/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/boekkooi/JqueryValidationBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/boekkooi/JqueryValidationBundle/?branch=master)
[![Total Downloads](https://poser.pugx.org/boekkooi/jquery-validation-bundle/downloads.svg)](https://packagist.org/packages/boekkooi/jquery-validation-bundle)
[![Latest Stable Version](https://poser.pugx.org/boekkooi/jquery-validation-bundle/v/stable.svg)](https://packagist.org/packages/boekkooi/jquery-validation-bundle)
[![License](https://poser.pugx.org/boekkooi/jquery-validation-bundle/license.svg)](https://packagist.org/packages/boekkooi/jquery-validation-bundle)
[![Reference Status](https://www.versioneye.com/php/boekkooi:jquery-validation-bundle/reference_badge.svg?style=flat)](https://www.versioneye.com/php/boekkooi:jquery-validation-bundle/references)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/e8faed88-613f-4530-8c54-6dfb823f588e/mini.png)](https://insight.sensiolabs.com/projects/e8faed88-613f-4530-8c54-6dfb823f588e)

Welcome! Did you ever have the need to have some Javascript form validation within your symfony application?
Well I did! So I made this little bundle to do it for me.

Do me a favor and give it a try and if you have any problem please create a issue or make a pull request.

Some of the things that the bundle supports:
- Automatic constraint mapping
- Form Collection (including prototype)
- Validation groups
- Data class constraints

Now let's go to the [documentation](docs/index.md) and get started.

[Install and configure](docs/install.md)
-------------
`composer require boekkooi/jquery-validation-bundle dev-master`

All the installation instructions are located in the [documentation](docs/install.md).

Demo
-------------
Yeah so .... nope there is no real demo **but** what you can do is the following:
```BASH
git clone https://github.com/boekkooi/JqueryValidationBundle.git
cd JqueryValidationBundle

curl -sS https://getcomposer.org/installer | php
php composer.phar install

php -S 0.0.0.0:8000 -t tests/Functional/web/
```
Now you can see the functional test pages on http://127.0.0.1:8000/, these should give you a good idea of what is possible.

License
-------------
This bundle is under the MIT license.

About
-----
This bundle is created and maintained by [Warnar Boekkooi](http://boekkooi.net/).
Special thanks go to the [Symfony](http://symfony.com/) community and [Jörn Zaefferer](http://jqueryvalidation.org/).

Alternatives
-----
If for some reason you don't like this bundle and you are looking for a alternative you may take a look at [FpJsFormValidatorBundle](https://packagist.org/packages/fp/jsformvalidator-bundle) or [UberFrontendValidationBundle](https://github.com/Sleepness/UberFrontendValidationBundle). 
It would also be appreciate if you create a issue telling why you don't like this bundle and what could be added/changed to make it more to your liking. 
