<?php

require_once __DIR__ . '/../Controller/TaskController.php';
require_once __DIR__ . '/../Controller/AuthController.php';

use Controller\TaskController;
use Controller\AuthController;

class CustomRouter {
    public static function getRoutes() {
        return [
            // Rotas para tarefas
            ['POST', '/apirestphp/tasks-create', [new TaskController(), 'create']],
            ['GET', '/apirestphp/tasks-read', [new TaskController(), 'read']],
            ['GET', '/apirestphp/tasks-readid', [new TaskController(), 'readid']],
            ['DELETE', '/apirestphp/tasks-delete', [new TaskController(), 'delete']],
            ['PUT', '/apirestphp/tasks-update', [new TaskController(), 'update']],

            // Rotas para autenticação
            ['POST', '/apirestphp/signup', [new AuthController(), 'signup']],
            ['POST', '/apirestphp/login', [new AuthController(), 'login']], 
        ];
    }

    public function handleRequest($routes) {
        // Obter o método HTTP e o caminho da URL da requisição
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $_SERVER['REQUEST_URI'];
        
        foreach ($routes as $route) {
            if ($route[0] == $method && $route[1] == $uri) {
                $action = $route[2];
                $action(); 
                return;
            }
        }
        
        // Se nenhuma rota corresponder, retorne um erro 404
        http_response_code(404);
        echo "404 Not Found";
    }
    
}
