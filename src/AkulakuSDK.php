<?php

namespace PHPAkulaku;

use PHPAkulaku\Exception\SdkException;
use PHPAkulaku\V1\{Shop,Token, Oauth, Seller,Reverse,GlobalProduct,Product,Order,Logistics,Category,Goods};

/**
 * @property-read Seller $seller
 * @property-read Shop $shop
 * @property-read Oauth $oauth
 * @property-read Token $token
 * @property-read Reverse $reverse
 * @property-read GlobalProduct $globalProduct
 * @property-read Product $product
 * @property-read Order $order
 * @property-read Logistics $logistics
 * @property-read Category $finance
 * @property-read Goods $fulfillment
 *
 * @method Shop shop()
 * @method Token token()
 * @method Oauth oauth()
 * @method Reverse reverse()
 * @method GlobalProduct globalProduct()
 * @method Product product()
 * @method Order order()
 * @method Logistics logistics()
 * @method Category finance()
 * @method Goods fulfillment()
 * @method Seller seller()
 */
class AkulakuSDK
{

    protected $defaultApiVersion = 'V1';

    protected $resources = [
        'shop',
        'token',
        'oauth',
        'reverse',
        'globalProduct',
        'product',
        'order',
        'logistics',
        'finance',
        'fulfillment',
        'seller'
    ];

    public $config = [
        'tiktokUrl'   => '',
        'appKey'      => '',
        'appSecret'   => '',
        'apiVersion'  => '',
        'accessToken' => '',
        'shopId'      => '',
    ];

    public function __construct($config)
    {
        $this->config = array_merge($this->config,[
            'apiVersion' => $this->defaultApiVersion, // 默认api版本为v1
        ], $config);

        $this->defaultApiVersion = $this->config['apiVersion']?: $this->defaultApiVersion;
    }

    public function __get($resourceName)
    {
        return $this->$resourceName();
    }

    public function __call($resourceName, $arguments)
    {
        if (!in_array($resourceName, $this->resources)) {
            throw new SdkException(sprintf('Invalid resource name %s. Pls check the API Reference to get the appropriate resource name.', $resourceName));
        }

        $resourceClassName = __NAMESPACE__ . "\\" . $this->defaultApiVersion . "\\" . \ucfirst($resourceName);

        $resource = new $resourceClassName($this);

        return $resource;
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/11/22 10:19
     * @param $config
     * @return AkulakuSDK
     */
    public static function config($config): AkulakuSDK
    {
        return new AkulakuSDK($config);
    }
}
