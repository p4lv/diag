<?php

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

require_once __DIR__.'/../vendor/autoload.php';

$locator = new FileLocator(__DIR__ . '/../config');

// DI container
$container = new DependencyInjection\ContainerBuilder;
$resolver = new LoaderResolver(
    [
        new YamlFileLoader($container, $locator),
        new PhpFileLoader($container, $locator),
    ]
);
$loader = new DelegatingLoader($resolver);
$loader->load('config-test.yml');

$container->compile();
