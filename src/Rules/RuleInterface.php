<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;

interface RuleInterface {

    public function setHttpResponse(HttpResponse $httpResponse);

    public function validate();

    public function getName();

    public function getErrorMessage();

}