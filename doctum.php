<?php

/**
 * Doctum configuration for generating API documentation.
 *
 * Run: composer docs
 * Serve locally: composer docs:serve
 */

use Doctum\Doctum;
use Doctum\RemoteRepository\GitHubRemoteRepository;
use Doctum\Version\GitVersionCollection;
use Symfony\Component\Finder\Finder;

$dir = __DIR__ . '/src';

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('build')
    ->exclude('vendor')
    ->exclude('tests')
    ->in($dir);

return new Doctum($iterator, [
    'title' => 'PHP FinanzOnline Webservices API Documentation',
    'build_dir' => __DIR__ . '/docs/api',
    'cache_dir' => __DIR__ . '/.doctum/cache',
    'remote_repository' => new GitHubRemoteRepository('CSoellinger/php-fon-webservices', dirname($dir)),
    'default_opened_level' => 2,
]);
