<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude([
        'var'
    ]);

$config = PhpCsFixer\Config::create()
    ->setFinder($finder)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'array_indentation' => true,
        'array_syntax' => ['syntax' => 'short'],
        'blank_line_before_statement' => [
            'statements' => [
                'break',
                'continue',
                'declare',
                'do',
                'for',
                'foreach',
                'if',
                'include',
                'include_once',
                'require',
                'require_once',
                'return',
                'switch',
                'throw',
                'try',
                'while',
                'yield',
            ],
        ],
        'class_attributes_separation' => [
            'elements' => [
                'method',
                'property',
            ],
        ],
        'concat_space' => ['spacing' => 'one'],
        'doctrine_annotation_indentation' => true,
        'doctrine_annotation_spaces' => [
            'after_argument_assignments' => false,
            'after_array_assignments_colon' => true,
            'after_array_assignments_equals' => false,
            'around_parentheses' => true,
            'before_argument_assignments' => false,
            'before_array_assignments_colon' => false,
            'before_array_assignments_equals' => false,
        ],
        'header_comment' => [
            'header' => '',
        ],
        'list_syntax' => [
            'syntax' => 'short',
        ],
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'no_multi_line',
        ],
        'native_function_invocation' => true,
        'no_superfluous_elseif' => true,
        'no_useless_else' => true,
        'ordered_class_elements' => true,
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order' => ['class', 'const', 'function'],
        ],
        'phpdoc_order' => true,
        'phpdoc_to_comment' => false,
        'phpdoc_types_order' => [
            'null_adjustment' => 'always_last',
        ],
        'self_accessor' => true,
        'visibility_required' => [
            'elements' => [
                'const',
                'method',
                'property',
            ],
        ],
    ])
;

$config->setCacheFile('var/.php_cs.cache');

return $config;
