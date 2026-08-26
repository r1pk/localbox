<?php

$finder = new PhpCsFixer\Finder();
$finder = $finder->in(__DIR__ . '/src');
$finder = $finder->append([__FILE__]);

$config = new PhpCsFixer\Config();
$config = $config->setRules([
    '@Symfony' => true,

    'braces_position' => false,
    'single_line_empty_body' => true,
    'single_line_throw' => false,

    'class_attributes_separation' => [
        'elements' => [
            'case' => 'one',
            'const' => 'one',
            'method' => 'one',
            'property' => 'one',
        ],
    ],
    'method_argument_space' => [
        'on_multiline' => 'ensure_fully_multiline',
    ],
    'trailing_comma_in_multiline' => [
        'elements' => ['arguments', 'array_destructuring', 'arrays', 'match', 'parameters'],
    ],

    'concat_space' => [
        'spacing' => 'one',
    ],
    'declare_equal_normalize' => [
        'space' => 'single',
    ],
    'yoda_style' => false,

    'global_namespace_import' => [
        'import_classes' => true,
        'import_constants' => false,
        'import_functions' => false,
    ],
    'phpdoc_line_span' => [
        'const' => 'single',
        'function' => 'multi',
        'method' => 'multi',
        'property' => 'single',
    ],
]);
$config->setIndent('    ');
$config->setLineEnding(chr(10));
$config->setFinder($finder);

return $config;
