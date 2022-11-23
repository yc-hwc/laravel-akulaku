<?php

namespace PHPAkulaku\V1\Traits;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

trait Api
{

    public $url;

    public $uri;

    public $fullUrl;

    protected $queryString = [];

    protected $commonHeaders = [];

    protected $timestamp;

    protected $body;

    protected $requestMethod = 'post';

    protected $timeout = 60;

    protected $times = 3;

    protected $sleep = 100;

    protected $httpClient;

    protected $response;

    protected $headers = [
        'Content-type' => 'application/json',
        'Accept'       => 'application/json',
    ];

    protected $options = [
        'verify' => false
    ];

    protected $akulakuSDK;

    /**
     * @Author: hwj
     * @DateTime: 2022/4/23 11:18
     * @return static
     */
    public function setHttpClient()
    {
        $this->httpClient = Http::withOptions($this->options)->timeout($this->timeout)->retry($this->times, $this->sleep);
        return $this;
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/23 11:19
     * @return PendingRequest
     */
    public function httpClient()
    {
        return $this->httpClient;
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/26 20:33
     * @param array $options
     * @return static
     */
    public function withOptions(array $options)
    {
        $this->options = array_merge($this->options, $options);
        $this->httpClient()->withOptions($this->options);
        return $this;
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/23 11:19
     * @param int $timeout
     * @return static
     */
    public function setTimeout($timeout = 60)
    {
        $this->timeout = $timeout;
        $this->httpClient->timeout($this->timeout);
        return $this;
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/23 11:19
     * @param int $times
     * @param int $sleep
     * @return static
     */
    public function setRetry(int $times, int $sleep = 0)
    {
        $this->times = $times;
        $this->sleep = $sleep;
        $this->httpClient->retry($times, $sleep);
        return $this;
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/23 11:20
     * @return string
     */
    protected function formatBody()
    {
        if ($this->requestMethod != 'post' || empty($this->body)) {
            return '';
        }

        return is_array($this->body) ? json_encode($this->body) : $this->body;
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/23 11:20
     * @param $requestMethod
     * @return static
     */
    public function setRequestMethod($requestMethod)
    {
        $this->requestMethod = $requestMethod;
        return $this;
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/23 11:20
     * @param mixed $body
     * @param string $contentType
     * @return static
     */
    public function withBody(mixed $body, $contentType = 'application/json')
    {
        $this->body = $body;
        $this->httpClient()->withBody($this->formatBody(), $contentType);
        return $this;
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/23 11:20
     * @param array $queryString
     * @return static
     */
    public function withQueryString(array $queryString)
    {
        $this->queryString  = $queryString;
        return $this;
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/23 11:20
     * @param mixed $headers
     * @return static
     */
    public function withHeaders(mixed $headers)
    {
        $this->headers = array_merge($this->headers, $headers);
        $this->httpClient()->withHeaders($this->headers);
        return $this;
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/20 17:48
     * @return array|mixed
     * @throws RequestException
     */
    public function post()
    {
        return $this->setRequestMethod('post')->run();
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/20 17:49
     * @return array|mixed
     * @throws RequestException
     */
    public function get()
    {
        return $this->setRequestMethod('get')->run();
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/20 17:49
     * @return array|mixed
     * @throws RequestException
     */
    public function run()
    {
        $resource = $this->fullUrl();

        $response = match ($this->requestMethod) {
            'get'  => $this->httpClient()->get($resource),
            'post' => $this->httpClient()->post($resource),
        };

        $this->setResponse($response);
        $response->throw();
        return $response->json()?: $response->body();
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/23 11:19
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/23 11:19
     * @param Response $response
     * @return Response
     */
    public function setResponse(Response $response)
    {
        return $this->response = $response;
    }

    public function fullUrl()
    {
        $this->generateUrl();
        return $this->fullUrl = sprintf('%s%s?%s', ...[
            $this->url,
            $this->uri,
            http_build_query($this->queryString?? [])
        ]);
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/23 11:18
     * @return static
     */
    protected function generateUrl()
    {
        $this->uri = strpos($this->childResources, '/') === 0? $this->childResources: $this->parentResource . '/' . $this->childResources;
        $this->url = $this->akulakuSDK->config['akulakuUrl'];
        $this->timestamp = time();
        $this->setApiCommonHeaders();
        return $this;
    }

    /**
     * 设置api公共请求头
     * @Author: hwj
     * @DateTime: 2022/4/23 11:22
     */
    protected function setApiCommonHeaders()
    {
        $akulakuSDK = &$this->akulakuSDK;

        $signArr = [
            'app-id'    => $akulakuSDK->config['appId'],
            'timestamp' => $this->timestamp,
        ];

        uksort($this->queryString, 'strcmp');

        $signStr = sprintf('%s%s&%s', ...[
            http_build_query($signArr),
            $this->queryString? http_build_query($this->queryString): '',
            is_array($this->body)? json_encode($this->body): ''
        ]);

        $this->commonHeaders = [
            'app-id'       => $akulakuSDK->config['appId'],
            'access-token' => $akulakuSDK->config['accessToken'],
            'sign'         => $this->generateSign($signStr, $akulakuSDK->config['privateKey']),
            'timestamp'    => $this->timestamp,
        ];

        $this->withHeaders(array_merge($this->headers, $this->commonHeaders));
    }

    /**
     * 生成签名
     * @Author: hwj
     * @DateTime: 2022/4/23 11:22
     * @param $had
     * @param $key
     * @return string
     */
    protected function generateSign($had, $privateKey)
    {
        $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($privateKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";
        $key = openssl_get_privatekey($privateKey);
        openssl_sign(base64_encode($had), $signature, $key, 'sha256WithRSAEncryption');
        openssl_free_key($key);
        $sign = base64_encode($signature);
        return $sign;
    }
}

