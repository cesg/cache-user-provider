<?php

$finder = PhpCsFixer\Finder::create()
    ->exclude(['resources', 'storage', 'bootstrap', 'public', 'node_modules', 'deployer'])
    ->notName('*.blade.php')
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
    ->in(__DIR__);

$config = new PhpCsFixer\Config();

return $config
    ->setRiskyAllowed(true)
    ->setRules([
        '@PHP80Migration' => true,
        '@PHP80Migration:risky' => true,
        '@Symfony' => true,
        '@Symfony:risky' => true,
        'declare_strict_types' => false,
        'native_function_invocation' => ['include' => ['@internal'], 'scope' => 'namespaced'],
        'php_unit_test_annotation' => ['style' => 'annotation'],
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'yoda_style' => false,
        'method_chaining_indentation' => true,
        'array_indentation' => true,
    ])
    ->setFinder($finder);
