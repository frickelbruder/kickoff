<?php
namespace Frickelbruder\KickOff\Http;

use Frickelbruder\KickOff\Configuration\TargetUrl;

class HttpResponse {

    /**
     * @var array
     */
    private $headers = array();

    /**
     * @var TargetUrl
     */
    private $targetUrl = null;

    /**
     * @var string
     */
    private $body = '';

    /**
     * @var string
     */
    private $status = 100;

    /**
     * @var float
     */
    private $transferTime = 0.0;

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

    /**
     * @return float
     */
    public function getTransferTime() {
        return $this->transferTime;
    }

    /**
     * @param float $transferTime
     */
    public function setTransferTime($transferTime) {
        $this->transferTime = $transferTime;
    }

    /**
     * @param TargetUrl $targetUrl
     */
    public function setRequest(TargetUrl $targetUrl) {
        $this->targetUrl = $targetUrl;
    }

    /**
     * @return TargetUrl
     */
    public function getRequest() {
        return $this->targetUrl;
    }
}