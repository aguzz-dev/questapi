<?php
namespace Lib;

class Route
{
    private static $routes = [];

    public static function get($url, $callback)
    {
        $url = trim($url, '/');
        self::$routes['GET'][$url] = $callback;
    }

    public static function post($url, $callback)
    {
        $url = trim($url, '/');
        self::$routes['POST'][$url] = $callback;
    }

    public static function put($url, $callback)
    {
        $url = trim($url, '/');
        self::$routes['PUT'][$url] = $callback;
    }

    public static function delete($url, $callback)
    {
        $url = trim($url, '/');
        self::$routes['DELETE'][$url] = $callback;
    }

    public static function dispatch()
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uri = trim($uri, '/');
        $uri = str_replace('questapi/', '', $uri);
    
        $method = $_SERVER['REQUEST_METHOD'];
        
        foreach(self::$routes[$method] as $route => $callback){
            if ($route == $uri) {
                if (is_callable($callback)) {
                    $callback();
                } else {
                    $controller = new $callback[0];
                    $response = $controller->{$callback[1]}();
                    
                    header('Content-Type: application/json');
                    echo json_encode($response);
                }
                return;
            }
        }
        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada"]);
    }
    
    
}

