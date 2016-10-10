<?php
namespace Frickelbruder\KickOff\Rules;

use Frickelbruder\KickOff\Rules\HtmlTagNotPresent;

class MetaGeneratorNotPresent extends HtmlTagNotPresent {

    public $name = 'MetaGeneratorNotPresent';

    protected $errorMessage = 'The <meta name="generator" content="..."> tag must not be present.';

    protected $xpath = '/html/head/meta[@name="generator"]/ @content';

}
