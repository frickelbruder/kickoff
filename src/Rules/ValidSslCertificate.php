<?php

namespace Frickelbruder\KickOff\Rules;

class ValidSslCertificate extends RuleBase
{

    public $name = 'ValidSslCertificate';

    public function validate()
    {
        $this->errorMessage = $this->httpResponse->getSslCertificateError();

        return $this->httpResponse->getSslCertificateError() === null;
    }
}
