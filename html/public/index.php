<?php

$autoload = require __DIR__ . '/../vendor/autoload.php';

if (
  php_sapi_name() !== 'cli' && // Pas en mode ligne de commande
  preg_match('/\.(?:png|jpg|jpeg|gif|svg|ico)$/', $_SERVER['REQUEST_URI']) // extension = asset
) {
  return false;
}

if (
    php_sapi_name() !== 'cli' && // Pas en mode ligne de commande
    preg_match('/\.(?:css|js)$/', $_SERVER['REQUEST_URI']) // extension = asset
) {
    include __DIR__ . '/assets/' . $_SERVER['REQUEST_URI'];
    return false;
}

use App\Controller\IndexController;
use App\Controller\UserController;
use App\DependencyInjection\Container;
use App\Repository\UserRepository;
use App\Routing\RouteNotFoundException;
use App\Routing\Router;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Driver\AttributeDriver;
use Doctrine\ORM\ORMSetup;
use Symfony\Bundle\FrameworkBundle\Routing\AnnotatedRouteControllerLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Loader\AnnotationDirectoryLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

// --- ENV VARS
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/../.env');
// --- ENV VARS

// --- DOCTRINE
$paths = ['src/Entity'];
$isDevMode = $_ENV['APP_ENV'] === 'dev';

$dbParams = [
  'driver'   => $_ENV['DB_DRIVER'],
  'host'     => $_ENV['DB_HOST'],
  'port'     => $_ENV['DB_PORT'],
  'user'     => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASSWORD'],
  'dbname'   => $_ENV['DB_NAME']
];

$config = ORMSetup::createAnnotationMetadataConfiguration($paths, $isDevMode, null, null, false);
$entityManager = EntityManager::create($dbParams, $config);

$driver = new AttributeDriver($paths);
$entityManager->getConfiguration()->setMetadataDriverImpl($driver);
// --- DOCTRINE

// --- TWIG
$loader = new FilesystemLoader(__DIR__ . '/../templates');
$twig = new Environment($loader, [
  'debug' => $_ENV['APP_ENV'] === 'dev',
  'cache' => __DIR__ . '/../var/cache/twig'
]);
// --- TWIG

// --- REPOSITORIES
$userRepository = new UserRepository($entityManager);
// --- REPOSITORIES

// --- CONTAINER
$container = new Container();
$container->set(EntityManager::class, $entityManager);
$container->set(Environment::class, $twig);
$container->set(UserRepository::class, $userRepository);
// --- CONTAINER

if (php_sapi_name() === 'cli') {
  return;
}

// Register the Composer autoloader with the AnnotationRegistry
AnnotationRegistry::registerLoader([$autoload, 'loadClass']);

// Create a new AnnotationDirectoryLoader to load routes from annotations
$autoload = new AnnotationDirectoryLoader(
    new FileLocator(__DIR__ . '/../src/Controller/'),
    new AnnotatedRouteControllerLoader(
        new AnnotationReader()
    )
);

// Load routes from annotated controllers
$routes = $autoload->load(__DIR__ . '/../src/Controller/');

// Initialize the RequestContext object
$context = RequestContext::fromUri($_SERVER['REQUEST_URI']);
$context->fromRequest(Request::createFromGlobals());

// Create a UrlMatcher to match the current request against the loaded routes
$matcher = new UrlMatcher($routes, $context);

// Retrieve the parameters from the context of the Request
$parameters = $matcher->match($context->getPathInfo());

// Extract the controller and action from the "_controller" parameter
$controllerInfo = explode('::',$parameters['_controller']);
$controller = $controllerInfo[0];
$action = $controllerInfo[1];

$router = new Router($container);

try {
  $router->execute($controller, $action, $parameters);
} catch (RouteNotFoundException $e) {
  http_response_code(404);
  echo "<p>Page non trouvée</p>";
  echo "<p>" . $e->getMessage() . "</p>";
}
