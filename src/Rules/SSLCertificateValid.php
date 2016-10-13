<?php

namespace Frickelbruder\KickOff\Rules;

class SSLCertificateValid extends ConfigurableRuleBase {

    public $name = 'SSLCertificateValid';

    public function validate()
    {

    	/*
    	 * So long as we're using Guzzle and unable to tell
    	 * Guzzle to verify the SSL before calling the rule,
    	 * we'll instead use another method that avoids
    	 * duplicating the HTTP request.
    	 */

    	if ($this -> httpResponse -> getRequest() -> scheme != 'https') {
    		$this->errorMessage = 'Rule requires https scheme to be used.';
    	} elseif (!$this -> httpResponse -> getRequest() -> port) {
    		$this->errorMessage = 'Rule requires port to be set.';
    	} else {
	    	$context = stream_context_create(array(
	    		'ssl' => array(
	    			'verify_peer' => false,
	    			'allow_self_signed' => true,
	    			'capture_peer_cert' => true
	    		)
	    	));

	    	/*
	    	 * Error handler needs to be overriden, as the
	    	 * stream_socket_client (like fopen) will throw
	    	 * a warning when the certificate is invalid.
	    	 */

	    	set_error_handler(function($errno, $errstr) {
	    		$this->errorMessage = $errstr;
	    	}, E_WARNING);

    		$client = stream_socket_client(sprintf("ssl://%s:%s", $this -> httpResponse -> getRequest() -> host,
    				$this -> httpResponse -> getRequest() -> port), $errno, $errstr, 5, STREAM_CLIENT_CONNECT, $context);

    		restore_error_handler();

    		if (!$client) {
    			$this->errorMessage = (!$errstr) ? 'The certificate is invalid (self-signed?)' : $errstr;
    		} elseif (!$errstr) {
    			$params = stream_context_get_params($client);
    			if (!isset($params['options']['ssl']['peer_certificate'])) {
    				$this->errorMessage = 'No certificate could be found.';
    			//} elseif (!openssl_x509_checkpurpose($params['options']['ssl']['peer_certificate'],
    			//		X509_PURPOSE_SSL_SERVER)) {
    			//	$this->errorMessage = 'The certificate is not meant to be used for server-side encryption.';
    			} else {
    				$x509 = openssl_x509_parse($params['options']['ssl']['peer_certificate']);

    				$validFrom = (!isset($x509['validFrom_time_t'])) ? 0 : $x509['validFrom_time_t'];
    				$validTo = (!isset($x509['validTo_time_t'])) ? 0 : $x509['validTo_time_t'];

    				if ($validFrom > time()) {
    					$this->errorMessage = 'The certificate is post-dated.';
    				} elseif ($validTo < time()) {
    					$this->errorMessage = 'The certificate is expired.';
    				} else {
    					return true;
    				}
    			}
    		} else {
    			$this->errorMessage = $errstr;
    		}
    	}

        return false;
    }

    public function allowMultipleTags($allowMultipleTags)
    {
        $this->allowMultipleTags = $allowMultipleTags;
    }
}
