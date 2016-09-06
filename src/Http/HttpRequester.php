<?php
namespace Frickelbruder\KickOff\Http;

use Frickelbruder\KickOff\Configuration\TargetUrl;


class HttpRequester {

    /**
     * @var HttpResponse[]
     */
    private $cache;

    private $client = null;

    /**
     * @param TargetUrl $targetUrl
     *
     * @return HttpResponse
     */
    public function request(TargetUrl $targetUrl) {
        $url = $targetUrl->getUrl();
        $cacheKey = $url . $targetUrl->method . json_encode($targetUrl->getHeaders());
        if(empty($this->cache[$cacheKey])) {
            $this->cache[ $cacheKey ] = $this->call( $targetUrl );
        }
        return $this->cache[ $cacheKey ];
    }

    private function call(TargetUrl $targetUrl) {
        $client = $this->getClient();
        $httpResponseFromWebsite = $client->request($targetUrl->method, $targetUrl->getUrl(), array('headers' => $this->getHeaders($targetUrl)));
        $headers = $httpResponseFromWebsite->getHeaders();
        foreach($headers as $name => $value) {
            if($name == 'x-encoded-content-encoding') {
                $headers['Content-Encoding'] = $value;
            }
        }
        $response = new HttpResponse();
        $response->setHeaders($headers);
        $response->setBody($httpResponseFromWebsite->getBody());
        $response->setStatus($httpResponseFromWebsite->getStatusCode());

        return $response;
    }

    private function getHeaders(TargetUrl $targetUrl) {
        $defaults = array('Accept-Encoding' => 'gzip');
        $headers = $targetUrl->getHeaders();

        $mergedHeaders = $defaults + $headers;

        return array_filter($mergedHeaders);
    }

    private function getClient() {
        if(!empty($this->client)) {
            return $this->client;
        }
        return new \GuzzleHttp\Client();
    }

    /**
     * @param null $client
     */
    public function setClient($client) {
        $this->client = $client;
    }



}