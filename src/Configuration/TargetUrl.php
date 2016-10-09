<?php
namespace Frickelbruder\KickOff\Configuration;

class TargetUrl {

    public $host = '';

    public $scheme = 'http';

    public $port = '';

    public $uri = '';

    public $method = 'get';

    public $headers = array();

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
        $url .= '/' . ltrim($this->uri, '/');
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

        $fields = array('scheme' => 'scheme', 'host' => 'host', 'port' => 'port', 'uri' => 'path');

        foreach($fields as $field => $component) {
            if(isset($urlParts[$component])) {
                $this->$field = $urlParts[$component];
            }
        }

        if(empty($this->host) && !empty($this->uri)) {
            $this->host = $this->uri;
            $this->uri = '';
            if(strpos($this->host, '/') !== false) {
                list( $this->host, $this->uri ) = explode( '/', $this->host, 2 );
            }
        }

    }

}