<?php

namespace app;

use app\Controller\AuthController;
use app\Controller\UsersController;
use app\Core\Config;
use app\Model\AdminModel;
use PDO;

class App
{
    private array $routes = [];
    private static PDO $database;
    private static Config $config;
    public function __construct(Config $config)
    {
        self::$config = $config;
        $this->setDatabase($config);
        if(!isset($_SESSION))session_start();
        //$_SESSION['is_admin'] = false;
        //AdminModel::isAdmin();

        $this->setRoute('default', UsersController::class, 'showUsers', true);

        $this->setRoute('/deleteUser', UsersController::class, 'deleteUser', true);
        $this->setRoute('/saveUser', UsersController::class, 'saveUser', true);
        $this->setRoute('/auth', AuthController::class, 'authPage');
        $this->setRoute('/signIn', AuthController::class, 'signIn');

        $route = $this->routes[$_SERVER['REQUEST_URI']] ?? $this->routes['default'];

        $this->process($route);

    }

    /**
     * Set database connection
     * If the application uses a database connection, it is necessary to run this method first in the constructor of the App class
     * @param Config $config
     * @return void
     */
    private function setDatabase(Config $config): void
    {
        $dsn = $config->get('dsn');
        $dbUser = $config->get('dbUser');
        $dbPassword = $config->get('dbPassword');

        self::$database = new PDO($dsn, $dbUser, $dbPassword);
    }

    /**
     * @return PDO
     */
    public static function getDatabase(): PDO
    {
        return self::$database;
    }

    /**
     * @return Config
     */
    public static function getConfig(): Config
    {
        return self::$config;
    }

    /**
     * Adds a new route for processing. If the route already exists, it will be overwritten
     * @param string $route
     * @param string $controller
     * @param string $method
     * @param bool $adminOnly
     * @return void
     */
    private function setRoute(string $route, string $controller, string $method, bool $adminOnly = false): void
    {
        $this->routes[$route] = [
            'controller' => $controller,
            'method' => $method,
            'adminOnly' => $adminOnly
        ];
    }

    /**
     * Evaluates the route and executes the required method in the controller
     * @param $route
     * @return void
     */
    private function process($route): void
    {
        if($route['adminOnly'] && !AdminModel::isAdmin()){
            header('Location: /auth');
            die();
        }

        $class = new $route['controller']();
        if (method_exists($class, $route['method'])) {
            $method = $route['method'];
            $class->$method();
        } else {
            http_response_code(404);
            die();
        }
    }
}