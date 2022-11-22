<?php


namespace PHPAkulaku\V1;


use PHPAkulaku\V1\Traits\ShopApi;

class AppControl extends AkulakuResource
{
    use ShopApi;

    protected $parentResource = '/api/open';
}