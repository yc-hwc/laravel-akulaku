<?php

namespace PHPAkulaku\V1;

use PHPAkulaku\V1\Traits\Api;

class Category extends AkulakuResource
{

    use Api;

    protected $parentResource = '/v1/open/goods/category';
}

