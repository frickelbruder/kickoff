<?php
namespace Frickelbruder\KickOff\Http;

use Frickelbruder\KickOff\Configuration\TargetUrl;
use GuzzleHttp\TransferStats;
use GuzzleHttp\Client;


class HttpRequester {

    /**
     * @var HttpResponse[]
     */
    private $cache;

    /**
     * @var Client
     */
    private $client = null;

    /**
     * @param TargetUrl $targetUrl
     *
     * @return HttpResponse
     */
    public function request(TargetUrl $targetUrl) {
        $cacheKey = $this->buildCacheKey( $targetUrl );
        if( empty( $this->cache[ $cacheKey ] ) ) {
            $this->cache[ $cacheKey ] = $this->call( $targetUrl );
        }

        return $this->cache[ $cacheKey ];
    }

    private function call(TargetUrl $targetUrl) {
        $response = new HttpResponse();

        $client = $this->getClient();
        $httpResponseFromWebsite = $client->request( $targetUrl->method,
            $targetUrl->getUrl(),
            $this->getOptionsArray( $targetUrl, $response )
        );

        $headers = $this->prepareResponseHeaders( $httpResponseFromWebsite->getHeaders() );
        $response->setHeaders( $headers );
        $response->setBody( $httpResponseFromWebsite->getBody() );
        $response->setStatus( $httpResponseFromWebsite->getStatusCode() );

        return $response;
    }

    private function getRequestHeaders(TargetUrl $targetUrl) {
        $defaults = array( 'Accept-Encoding' => 'gzip' );
        $headers = $targetUrl->getHeaders();

        $mergedHeaders = $defaults + $headers;

        return array_filter( $mergedHeaders );
    }

    /**
     * @return Client
     */
    private function getClient() {
        if( !empty( $this->client ) ) {
            return $this->client;
        }

        return new Client();
    }

    /**
     * @param null $client
     */
    public function setClient($client) {
        $this->client = $client;
    }

    /**
     * @param TargetUrl $targetUrl
     *
     * @return string
     */
    protected function buildCacheKey(TargetUrl $targetUrl) {
        $url = $targetUrl->getUrl();
        $method = $targetUrl->method;
        $headers = $targetUrl->getHeaders();
        $cacheKey = $url . $method . json_encode( $headers );

        return $cacheKey;
    }

    /**
     * @param array $headers
     *
     * @return array
     */
    private function prepareResponseHeaders(array $headers) {
        foreach( $headers as $name => $value ) {
            if( $name == 'x-encoded-content-encoding' ) {
                $headers['Content-Encoding'] = $value;
            }
        }
        return $headers;
    }

    /**
     * @param TargetUrl $targetUrl
     * @param HttpResponse $response
     *
     * @return array
     */
    private function getOptionsArray(TargetUrl $targetUrl, HttpResponse $response) {
        return array(
            'headers' => $this->getRequestHeaders( $targetUrl ),
            'on_stats' => function(TransferStats $stats) use ($response) {
                $response->setTransferTime( $stats->getTransferTime() );
            }
        );
    }


}