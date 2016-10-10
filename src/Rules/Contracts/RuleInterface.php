<?php
namespace Frickelbruder\KickOff\Rules\Contracts;

use Frickelbruder\KickOff\Http\HttpResponse;

interface RuleInterface {

    function setHttpResponse(HttpResponse $httpResponse);

    function validate();

    function getName();

    function getErrorMessage();

}
