<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Http\HttpResponse;

interface Rule {

    public function setItemToValidate(HttpResponse $item);

    public function validate();

    public function getName();

    public function getErrorMessage();

}