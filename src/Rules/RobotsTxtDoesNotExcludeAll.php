<?php

namespace Frickelbruder\KickOff\Rules;

class RobotsTxtDoesNotExcludeAll extends RuleBase {

    public function validate()
    {
        $regex = "~User-Agent:\s*\*\s+Disallow:\s*/\s*$~";
        $body = $this->httpResponse->getBody();

        return preg_match($regex, $body) === 0;
    }
}