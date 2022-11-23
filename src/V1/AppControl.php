<?php

namespace PHPAkulaku\V1;

use PHPAkulaku\V1\Traits\Api;

class AppControl extends AkulakuResource
{

    use Api;

    protected $parentResource = '/v1/open/app';
}
