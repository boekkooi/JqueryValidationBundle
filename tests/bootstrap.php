<?php
$loader = @include __DIR__ . '/../vendor/autoload.php';
if (!$loader) {
    die(<<<'EOT'
You must set up the project dependencies, run the following commands:
wget http://getcomposer.org/composer.phar
php composer.phar install
EOT
    );
}

\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(array($loader, 'loadClass'));

// Thanks to https://github.com/symfony/symfony/issues/12710 we need to add the following:
\PHPUnit_Framework_Error_Deprecated::$enabled = false;
