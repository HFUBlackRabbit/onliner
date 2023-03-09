<?php

namespace App;

use App\Models\UserModel;
use JetBrains\PhpStorm\NoReturn;

class App
{
    private static ?App $instance = null;
    public DB $db;

    private static $user;

    private function __construct(
        public Router $router = new Router(),
        private readonly View $view = new View()
    )
    {
        $dbConfig = require(ROOT . '/config/db.php');
        $dsn = sprintf('mysql:dbname=%s;host=%s:%s;charset=%s',
            $dbConfig['database'],
            $dbConfig['host'],
            $dbConfig['port'],
            $dbConfig['charset']
        );
        $this->db = new DB($dsn, $dbConfig['username'], $dbConfig['password']);
    }

    public static function getInstance(): App
    {
        if (self::$instance == null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    public function handle()
    {
        $handler = $this->router->resolve();

        $controller = new $handler[0]();

        echo call_user_func([$controller, $handler[1]]);
    }

    /**
     * @param string $uri
     * @param int $code
     * @return void
     */
    #[NoReturn] public function redirect(string $uri, int $code = 301): void
    {
        http_response_code($code);
        header('Location: ' . $uri);
        exit();
    }

    /**
     * @param string $message
     * @param int $code
     * @return void
     */
    #[NoReturn]
    public function abort(string $message, int $code): void
    {
        http_response_code($code);
        die($message);
    }

    /**
     * @param string $template
     * @param array $data
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function render(string $template, array $data = []): string {
        $data['user'] = App()->user();
        return $this->view->render($template . '.twig', $data);
    }

    public function user(): UserModel|null
    {
        if (empty($_SESSION['userId'])) {
            return null;
        }

        if (self::$user) {
            return self::$user;
        }

        self::$user = UserModel::findOneById($_SESSION['userId']);
        return self::$user;
    }
}