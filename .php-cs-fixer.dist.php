<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')
;

return new PhpCsFixer\Config()
    ->setRules([
        '@PSR12' => true,
        '@PER-CS' => true,
        '@PHP84Migration' => true,
        'array_syntax' => ['syntax' => 'short'],
    ])
    ->setFinder($finder)
;
