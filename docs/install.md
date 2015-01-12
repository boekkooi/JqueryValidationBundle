Installation
============

Add BoekkooiJqueryValidationBundle by running the command:
```
composer require boekkooi/jquery-validation-bundle dev-master
```

Enable the bundle in the kernel:
```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Boekkooi\Bundle\JqueryValidationBundle\BoekkooiJqueryValidationBundle(),
    );
}
```

Default configuration:
```yaml
# app/config/config.yml
boekkooi_jquery_validation:
    form:
        enabled: true # Set to false to disable the form constraints being parsed/converted by default
        additionals: # Set to true if jquery validate additional-method.js is included
            accept: false
            ipv4: false
            ipv6: false
            iban: false
            luhn: false
            pattern: false
            time: false
            one_or_other: false
            required_group: false
    twig:
        enabled: true # Register the twig extension
```

[Get started](usage.md)