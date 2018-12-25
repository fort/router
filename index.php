<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'controllers/Users.php';
require_once 'mvc/Dispatcher.php';
require_once 'mvc/Router.php';
require_once 'mvc/Request.php';
require_once 'mvc/Response.php';

class App
{
	protected $request;
	protected $response;

    protected $routes = [];

	public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
    }

    public function get ($uri_pattern, $callback)
	{
		try {
            $router = new Router($uri_pattern, $callback);
        }catch (Exception $e) {
		    throw $e;
        }

		$this->routes[] = $router;
	}

	public function run ()
	{
        $this->handleRequest();
        print_r( $this->response->getBody() );

	}

	protected function handleRequest ()
	{
		$uri = $_SERVER['REQUEST_URI'];

		foreach ($this->routes as $router) {
			if ($router->match($uri))
			{
				try {
                    $dispatcher = new Dispatcher($this->request, $router);
                    $result = $dispatcher->processRequest();
                    $this->response->setBody($result);
                }catch (Exception $e) {
                    $this->response->setBody($e->getMessage());
                }
                return;
			}
		}

        $this->response->setBody('router not found!');
	}
}

$app = new App();
$app->get('/', function () { return 'root';});
$app->get('/home',function() { return 'home';});
$app->get('/users', 'Users@getList');
$app->get('/users/{status}/{role}', 'Users@getFilteredUsers');
$app->run();