<?php

namespace Stars\Core\Service;

class RequestService
{
    private $get;

    private $post;

    private $cookie;

    private $files;

    private $routes;

    protected function __construct($get, $post, $cookie, $files, $server, $routes) {
        $this->get = $get;
        $this->post = $post;
        $this->cookie = $cookie;
        $this->files = $files;
        $this->server = $server;
        $this->routes = $routes;
    }

    /**
     * @return Stars\Core\RequestService
     * 
     */
    public static function initialize($routes)
    {
        //handle php built in
        $server = $_SERVER;
        if ('cli-server' === PHP_SAPI) {
            if (array_key_exists('HTTP_CONTENT_LENGTH', $_SERVER)) {
                $server['CONTENT_LENGTH'] = $_SERVER['HTTP_CONTENT_LENGTH'];
            }
            if (array_key_exists('HTTP_CONTENT_TYPE', $_SERVER)) {
                $server['CONTENT_TYPE'] = $_SERVER['HTTP_CONTENT_TYPE'];
            }
        }
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $server, $routes);
    }

    /**
     * Return all parameters from $_GET
     * @return array
     */
    public function get()
    {
        return $this->get;
    }

    /**
     * Return all parameters from $_POST
     * @return array
     */
    public function post()
    {
        return $this->post;
    }

    /**
     * Return all parameters from $_COOKIE
     * @return array
     */
    public function cookie()
    {
        return $this->cookie;
    }

    /**
     * Return all parameters from $_SERVER
     * @return array
     */
    public function server()
    {
        return $this->server;
    }

    /**
     * Return a single param from defined type
     * Ex: age, post => $_POST['age']
     * @param string $param_name
     * @param string $type
     * @return mixed
     */
    public function param($param_name, $type)
    {
        $list = $this->$type;
        if(array_key_exists($param_name, $list)) {
            return $list[$param_name];
        }
        return null;
    }

    /**
     * Match the url to a route
     * @param $route_url
     * @return array Return a matched route
     */
    public function getRoute($route_url)
    {
        foreach ($this->routes as $route) {
            if (preg_match($route['url'], $route_url, $matches)) {
                if ($route['type'] == 'segment') {
                    $final_route = array(
                        'module' => $matches[substr($route['module'],1,1)],
                        'controller' => $matches[substr($route['controller'],1,1)],
                        'action' => $matches[substr($route['action'],1,1)],
                    );

                    if (array_key_exists('params', $route)) {
                        foreach ($route['params'] as $param => $value) {
                            $this->get[$param] = $matches[substr($value,1,1)];
                        }
                    }
                } else {
                    $final_route = array(
                        'module' => $route['module'],
                        'controller' => $route['controller'],
                        'action' => $route['action'],
                    );
                }

                return $final_route;
            }

        }
        
        return false;
    }

    /**
     * Check if a request is post
     * @return boolean
     */
    public function isPost()
    {
        $request = $this->server();
        if ($request["REQUEST_METHOD"] == 'POST') {
            return true;
        }
        return false;
    }

}