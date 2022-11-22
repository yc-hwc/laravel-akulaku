<?php


namespace PHPAkulaku\V1;

use PHPAkulaku\V1\Traits\ShopApi;

class Order extends AkulakuResource
{
    use ShopApi;

    protected $parentResource = '/v1/open/order';
}
