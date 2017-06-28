<?php

namespace Stars\Core\Service;

class RequestService
{
    private $get;

    private $post;

    private $cookie;

    private $files;

    protected function __construct($get, $post, $cookie, $files, $server) {
        $this->get = $get;
        $this->post = $post;
        $this->cookie = $cookie;
        $this->files = $files;
        $this->server = $server;
    }

    /**
     * @return Stars\Core\RequestService
     * 
     */
    public static function initialize()
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
        return new static($_GET, $_POST, $_COOKIE, $_FILES, $server);
    }

    public function get() {
        return $this->get;
    }

    public function post() {
        return $this->post;
    }

    public function cookie() {
        return $this->cookie;
    }

    public function server() {
        return $this->server;
    }

}