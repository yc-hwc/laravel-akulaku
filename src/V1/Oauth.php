<?php


namespace PHPAkulaku\V1;

use PHPAkulaku\V1\Traits\TokenApi;

class Oauth extends AkulakuResource
{

    use TokenApi;

    protected $parentResource = '/oapi/auth/oauth';
}
