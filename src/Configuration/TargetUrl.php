<?php
namespace Frickelbruder\KickOff\Configuration;

class TargetUrl {

    public static $components = array('host', 'port', 'path', 'scheme');

    public $host = '';

    public $scheme = 'http';

    public $port = '';

    public $path= '';

    public $method = 'get';

    public $headers = array();

    public $auth = array();

    public function __construct($url = null) {
        if(!empty($url)) {
            $this->parseString($url);
        }
    }

    public function getUrl() {
        $url = $this->scheme . '://' . $this->host;
        if(!empty($this->port) && is_numeric($this->port)) {
            $url .= ':'.$this->port;
        }
        $url .= '/' . ltrim($this->path, '/');
        return $url;
    }

    public function addHeader($header, $value) {
        $this->headers[$header] = $value;
    }

    public function getHeaders() {
        return $this->headers;
    }

    /**
     * @param string $url
     */
    private function parseString($url) {

        $urlParts = parse_url($url);

        foreach(self::$components as $component) {
            if(isset($urlParts[$component])) {
                $this->$component = $urlParts[$component];
            }
        }

        if(empty($this->host) && !empty($this->path)) {
            $this->host = $this->path;
            $this->path = '';
            if(strpos($this->host, '/') !== false) {
                list( $this->host, $this->path ) = explode( '/', $this->host, 2 );
            }
        }

    }

    public function setCredentials($username, $password) {
        $this->auth = array('username' => $username, 'password' => $password);
    }

    public function requiresAuth() {
        return !empty($this->auth);
    }

}