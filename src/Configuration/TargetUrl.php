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

        $urlParts = parse_url($url);

        $targetUrl = new self;
        $targetUrl->host = isset($urlParts['host']) ? $urlParts['host'] : '' ;
        $targetUrl->scheme = isset($urlParts['scheme']) ?  $urlParts['scheme'] . '://' : 'http:/';
        $targetUrl->port = isset($urlParts['port']) ? $urlParts['port'] : '';
        $targetUrl->uri = isset($urlParts['path']) ? $urlParts['path'] : '';

        return $targetUrl;
    }

}