<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\Exceptions\HeaderNotFoundException;

abstract class HttpRuleBase extends RuleBase {

    protected function findHeader($headerName, $normalize = true) {
        $loweredHeaderName = strtolower($headerName);
        $headers = $this->httpResponse->getHeaders();
        foreach($headers as $key => $header) {
            if(strtolower($key) == $loweredHeaderName) {
                if($normalize && is_array($header) ) {
                    return implode("\n", $header);
                }
                return $header;
            }
        }
        throw new HeaderNotFoundException();
    }

}