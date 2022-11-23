# laravel-akulaku
akulaku v1 SDK

#### 安装教程
````
composer require yc-hwc/laravel-akulaku
````

### 用法
***

#### 配置
````
    $config = [
        'akulakuUrl'  => '',
        'appId'       => '',
        'accessToken' => '',
        'privateKey'  => '',
    ];
    
    $tiktokSDK = \PHPAkulaku\AkulakuSDK::config($config);
````
#### [店铺授权](https://developer.akulaku.com/documentation?filename=overview%2Fopen-api-authentication.md)
````
    $config = [
        'akulakuUrl' => '',
    ];
    $akulakuSDK = AkulakuSDK::config($config);

    return ['redirectUrl' => $akulakuSDK->shopAuth()
        ->api('login')
        ->withQueryString([
            'clientId'     => '',
            'responseType' => 'code',
            'scope'        => 'all',
            'redirectUri'  => '',
        ])
        ->fullUrl()];
````
#### [订单列表](https://developer.akulaku.com/documentation?filename=order%2Forder-list.md)
````
    $config = [
        'akulakuUrl'  => '',
        'appId'       => '',
        'accessToken' => '',
        'privateKey'  => '',
    ];
    $akulakuSDK = \PHPAkulaku\AkulakuSDK::config($config);
    $response = $akulakuSDK->order()->api('list') // /v1/open/order/list
        ->withBody([
            'pageSize' => 100,
        ])->post();
    print_r($response);
    
    tips: /开头为绝对路径uri,不是/开头为相对路径uri,建议使用绝对路径uri
````
