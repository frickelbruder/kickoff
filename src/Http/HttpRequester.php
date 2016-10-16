<?php
namespace Frickelbruder\KickOff\Http;

use Frickelbruder\KickOff\Configuration\TargetUrl;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
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
        $websiteResponse = false;

        try {
            $client = $this->getClient();
            $websiteResponse = $client->request( $targetUrl->method,
                $targetUrl->getUrl(),
                $this->getOptionsArray( $targetUrl, $response )
            );
            $headers = $this->prepareResponseHeaders( $websiteResponse->getHeaders() );
            $response->setHeaders( $headers );
            $response->setBody( $websiteResponse->getBody() );
            $response->setStatus( $websiteResponse->getStatusCode() );
        } catch(ClientException $e) {
            $websiteResponse = $e->getResponse();
        } catch(ConnectException $e) {
            $exceptionMessage = $e->getMessage();
            $response->setBody( $exceptionMessage );
            $response->setStatus( 404 );
        }

        if ($websiteResponse) {
            $headers = $this->prepareResponseHeaders( $websiteResponse->getHeaders() );
            $response->setHeaders( $headers );
            $response->setBody( $websiteResponse->getBody() );
            $response->setStatus( $websiteResponse->getStatusCode() );
        }
        $response->setRequest($targetUrl);

        return $response;
    }

    /**
     * @return Client
     */
    private function getClient() {
        if( !empty( $this->client ) ) {
            return $this->client;
        }

        return new Client(array('verify'=>false));
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
            'headers' => $targetUrl->getHeaders(),
            'on_stats' => function(TransferStats $stats) use ($response) {
                $response->setTransferTime( $stats->getTransferTime() );
            }
        );
    }


}
