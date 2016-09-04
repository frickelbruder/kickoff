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
        if(empty($this->cache[$url])) {
            $this->cache[ $url ] = $this->call( $url );
        }
        return $this->cache[ $url ];
    }

    private function call($url) {
        $client = $this->getClient();
        $httpResponseFromWebsite = $client->get($url);
        $response = new HttpResponse();
        $response->setHeaders($httpResponseFromWebsite->getHeaders());
        $response->setBody($httpResponseFromWebsite->getBody());
        $response->setStatus($httpResponseFromWebsite->getStatusCode());

        return $response;
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