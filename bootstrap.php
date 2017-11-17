<?php



use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\DependencyInjection;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


require_once __DIR__.'/vendor/autoload.php';

$request = Request::createFromGlobals();

$locator = new FileLocator(__DIR__ . '/config');

// DI container
$container = new DependencyInjection\ContainerBuilder;
$resolver = new LoaderResolver(
    [
        new YamlFileLoader($container, $locator),
        new PhpFileLoader($container, $locator),
    ]
);
$loader = new DelegatingLoader($resolver);
$loader->load('config-development.yml');

$container->compile();

// routing
$loader = new Routing\Loader\YamlFileLoader($locator);
$context = new Routing\RequestContext();
$context->fromRequest($request);
$matcher = new Routing\Matcher\UrlMatcher(
    $loader->load('routing.yml'),
    $context
);

$parameters = $matcher->match($request->getPathInfo());

foreach ($parameters as $key => $value) {
    $request->attributes->set($key, $value);
}


$command = $request->getMethod() . $request->get('action');

//dump($container->get(Diag\Storage\Sqlite::class))
//;
//dump(new Diag\Storage\Sqlite);

try {
    $controller = $container->get($request->get('controller'));
    $response = $controller->{$command}($request) ?? new JsonResponse(['status' => 'error', 'message' => 'Unknown error'], 500);

} catch (\Exception $exception) {
    $data = [
        'status' => 'error',
        'message' => $exception->getMessage(),
    ];
    $resposne = new JsonResponse($data);

} catch (\TypeError $error) {
    $data = [
        'status' => 'error',
        'message' => $error->getMessage(),
    ];
    $resposne = new JsonResponse($data);
}

if($response instanceof Response)  {
    $response->send();
}
