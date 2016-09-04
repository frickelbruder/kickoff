<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;

abstract class HttpRuleBase extends RuleBase {

    protected function findHeader($headerName) {
        $loweredHeaderName = strtolower($headerName);
        $headers = $this->httpResponse->getHeaders();
        foreach($headers as $key => $header) {
            if(strtolower($key) == $loweredHeaderName) {
                return $header;
            }
        }
        throw new HeaderNotFoundException();
    }

}