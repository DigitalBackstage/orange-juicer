<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->in([__DIR__ . '/src'])
;

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::PSR2_LEVEL)
    ->fixers([
        'duplicate_semicolon',
        'empty_return',
        'join_function',
        'multiline_array_trailing_comma',
        'newline_after_open_tag',
        'object_operator',
        'ordered_use',
        'remove_lines_between_uses',
        'short_array_syntax',
        'standardize_not_equal',
        'unalign_double_arrow',
        'unalign_equals',
        'unused_use',
        'whitespacy_lines',
    ])
    ->setUsingCache(true)
    ->finder($finder)
;
