<?php
namespace Frickelbruder\KickOff\Http;

use Frickelbruder\KickOff\Configuration\TargetUrl;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
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

    private function call(TargetUrl $targetUrl, $verifySsl = true) {
        $response = new HttpResponse();

        $client = $this->getClient();
        try {
            $websiteResponse = $client->request( $targetUrl->method,
                $targetUrl->getUrl(),
                array_merge(array('verify' => $verifySsl), $this->getOptionsArray( $targetUrl, $response ))
            );
            $headers = $this->prepareResponseHeaders( $websiteResponse->getHeaders() );
            $response->setHeaders( $headers );
            $response->setBody( $websiteResponse->getBody() );
            $response->setStatus( $websiteResponse->getStatusCode() );
        } catch(ClientException $e) {
            $websiteResponse = $e->getResponse();
        } catch(RequestException $e) {
            $websiteResponse = false;
            $exceptionMessage = $e->getMessage();

            // Check for invalid SSL certificate: If we encounter a certificate error, we will remember
            // the error message and repeat the request with disabled certificate verification. That way,
            // a website with an invalid certificate can still be checked.
            $magicString = 'SSL certificate problem:';
            $magicOffset = strpos($exceptionMessage, $magicString);
            if ($magicOffset !== false && $verifySsl) {
                $response = $this->call($targetUrl, false);

                $certificateErrorMessage = trim(substr($exceptionMessage, $magicOffset + strlen($magicString)));
                $response->setSslCertificateError($certificateErrorMessage);

                return $response;
            }
        }

        if ($websiteResponse) {
            $headers = $this->prepareResponseHeaders( $websiteResponse->getHeaders() );
            $response->setHeaders( $headers );
            $response->setBody( $websiteResponse->getBody() );
            $response->setStatus( $websiteResponse->getStatusCode() );
        } else {
//            $headers = $this->prepareResponseHeaders( $websiteResponse->getHeaders() );
//            $response->setHeaders( $headers );
            $response->setBody( $exceptionMessage );
            $response->setStatus( 404 );
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
            'headers' => $targetUrl->getHeaders(),
            'on_stats' => function(TransferStats $stats) use ($response) {
                $response->setTransferTime( $stats->getTransferTime() );
            }
        );
    }


}
