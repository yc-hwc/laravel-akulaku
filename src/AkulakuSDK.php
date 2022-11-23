<?php


namespace PHPAkulaku;

use PHPAkulaku\Exception\SdkException;
use PHPAkulaku\V1\{AppControl, ShopAuth, Category, Goods, Logistics, Oauth, Order, Shop};

/**
 * @property-read AppControl $appControl
 * @property-read ShopAuth $shopAuth
 * @property-read Category $category
 * @property-read Goods $goods
 * @property-read Logistics $logistics
 * @property-read Oauth $oauth
 * @property-read Order $order
 * @property-read Shop $shop
 * @method AppControl appControl()
 * @method ShopAuth shopAuth()
 * @method Category category()
 * @method Goods goods()
 * @method Logistics logistics()
 * @method Oauth oauth()
 * @method Order order()
 * @method Shop shop()
 */

class AkulakuSDK
{

    protected $defaultApiVersion = 'V1';

    protected $resources = [
        'appControl',
        'shopAuth',
        'category',
        'goods',
        'logistics',
        'oauth',
        'order',
        'shop',
    ];

    public $config = [
        'akulakuUrl'  => '',
        'appId'       => '',
        'accessToken' => '',
        'privateKey'  => '',
        'apiVersion'  => '',
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

