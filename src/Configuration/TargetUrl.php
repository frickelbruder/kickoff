<?php
namespace Frickelbruder\KickOff\Configuration;

class TargetUrl {

    public $host = '';

    public $scheme = 'http://';

    public $port = '';

    public $uri = '';

    public $method = 'get';

    public $headers = array();

    public function getUrl() {
        $url = $this->scheme . $this->host;
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

    public static function fromString($url) {

        $components = parse_url($url);

        $targetUrl = new self;
        $targetUrl->host = $components['host'];
        $targetUrl->scheme = $components['scheme'] . '://';
        $targetUrl->port = $components['port'];
        $targetUrl->uri = $components['path'];

        return $targetUrl;
    }

}