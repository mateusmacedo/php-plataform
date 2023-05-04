<?php

declare(strict_types=1);

return (new PhpCsFixer\Config())
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setRules([
        '@PSR12' => true,
        '@PhpCsFixer' => true,
        'blank_line_after_opening_tag' => true,
        'blank_line_before_statement' => ['statements' => []],
        'control_structure_braces' => true,
        'single_blank_line_at_eof' => true,
        'statement_indentation' => true,
        'no_multiple_statements_per_line' => true,
        'declare_parentheses' => true,
        'single_space_around_construct' => true,
        'compact_nullable_typehint' => true,
        'concat_space' => ['spacing' => 'one'],
        'declare_equal_normalize' => ['space' => 'none'],
        'declare_strict_types' => true,
        'function_typehint_space' => true,
        'method_argument_space' => ['on_multiline' => 'ensure_fully_multiline'],
        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'new_with_braces' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => [
            'tokens' => [
                'break',
                'case',
                'continue',
                'curly_brace_block',
                'default',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'switch',
                'throw',
                'use',
            ]
        ],
        'no_leading_import_slash' => true,
        'no_leading_namespace_whitespace' => true,
        'no_trailing_comma_in_singleline' => true,
        'no_unused_imports' => true,
        'no_whitespace_in_blank_line' => true,
        'php_unit_internal_class' => [],
        'php_unit_test_class_requires_covers' => false,
        'return_assignment' => false,
        'return_type_declaration' => ['space_before' => 'none'],
        'single_import_per_statement' => false,
        'single_trait_insert_per_statement' => false,
        'trailing_comma_in_multiline' => [],
        'no_superfluous_phpdoc_tags' => false,
        'yoda_style' => ['always_move_variable' => false, 'equal' => true, 'identical' => true, 'less_and_greater' => null],
        'fully_qualified_strict_types' => true,
        'group_import' => true,
        'no_unneeded_import_alias' => true,
        'global_namespace_import' => [
            'import_classes' => true,
            'import_constants' => true,
            'import_functions' => true,
        ],
    ])->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude(['bootstrap', 'storage', 'vendor'])
            ->notName(['_*.php'])
            ->in(__DIR__)
    );
