<?php
namespace App;
// cette classe est le coeur de l'application, elle orchestre le conteneur et le routeur
class Application
{
    private Container $container;
    private Router $router;
    public function __construct(string $basePath)
    {
        $this->container = new Container();
        $this->router = new Router();
        $this->container->set('view', function () use ($basePath) {
            return new View($basePath . '/views');
        });
        $this->container->set('router', function () {
            return $this->router;
        });
    }

    public function container(): Container
    {
        return $this->container;
    }
    public function router(): Router
    {
        return $this->router;
    }

    public function run(): void
    {
        $request  = Request::fromGlobals();
        $response = $this->router->resolve($request, $this->container);
        $response->send();
    }
}
