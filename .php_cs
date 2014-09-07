<?php
$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in(__DIR__ . '/src')
;

return Symfony\CS\Config\Config::create()
    ->fixers(array(
        'encoding',
        'linefeed',
        'indentation',
        'trailing_spaces',
        'unused_use',
        'visibility',
        'short_tag',
        'php_closing_tag',
        'return',
        'braces',
        'lowercase_constants',
        'lowercase_keywords',
        'include',
        'function_declaration',
        'controls_spaces',
        'spaces_cast',
        'elseif',
        'eof_ending',
        'standardize_not_equal',
        'new_with_braces'
    ))
    ->finder($finder)
;

