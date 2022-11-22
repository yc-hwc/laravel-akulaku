<?php


namespace PHPAkulaku\V1;


use PHPAkulaku\V1\Traits\ShopApi;

class Logistics extends AkulakuResource
{
    use ShopApi;

    protected $parentResource = '/v1/open/delivery';
}
