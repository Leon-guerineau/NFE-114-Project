<?php

namespace App\Routing;

use App\Routing\Attribute\Route;
use App\Utils\Filesystem;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionMethod;

class Router
{
    private ContainerInterface $container;
    private const CONTROLLERS_DIRECTORY = __DIR__ . '/../Controller';
    private const CONTROLLERS_NAMESPACE = "App\\Controller\\";

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Executes router on specified URI and HTTP Method
     *
     * @param string $controller
     * @param string $action
     * @param array|null $parameters
     * @return void
     */
    public function execute(string $controller, string $action, array $parameters = null): void
    {
        $constructorParams = $this->getMethodParams($controller, '__construct');
        $controllerInstance = new $controller(...$constructorParams);

        $response = $controllerInstance->$action($parameters);
        echo $response ? $response->getContent() : null;
    }

    /**
     * Resolve & build method's parameters
     *
     * @param string $controller Controller's FQCN
     * @param string $method
     * @return array Empty if controller doesn't have any parameter
     */
    private function getMethodParams(string $controller, string $method): array
        // TODO-LEON : Change to getConstructParams()
    {
        $methodInfos = new ReflectionMethod($controller . '::' . $method);
        $methodParameters = $methodInfos->getParameters();
        $params = [];

        foreach ($methodParameters as $param) {
            $paramName = $param->getName();
            $paramType = $param->getType()->getName();

            if ($this->container->has($paramType)) {
                $params[$paramName] = $this->container->get($paramType);
            }
        }

        return $params;
    }
}
