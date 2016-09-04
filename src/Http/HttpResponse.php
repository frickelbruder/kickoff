<?php
namespace Frickelbruder\KickOff\Http;

class HttpResponse {

    private $headers = array();

    private $body = '';

    private $status = '';

    /**
     * @return array
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders($headers) {
        $this->headers = $headers;
    }

    /**
     * @return string
     */
    public function getBody() {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body) {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }



}