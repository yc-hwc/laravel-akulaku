<?php



namespace PHPAkulaku\V1;

use PHPAkulaku\AkulakuSDK;

abstract class AkulakuResource
{

    protected $parentResource;

    protected $childResources;

    protected $akulakuSDK;

    public function __construct(AkulakuSDK $akulakuSDK)
    {
        $this->akulakuSDK = $akulakuSDK;
        $this->setHttpClient();
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/25 12:14
     * @param $resourceName
     * @return static
     */

    public function __get($resourceName)
    {
        return $this->$resourceName();
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/11/22 10:20
     * @param $resourceName
     * @param $arguments
     * @return static
     */
    public function __call($resourceName, $arguments)
    {
        return $this->api($resourceName);
    }

    /**
     * @Author: hwj
     * @DateTime: 2022/4/25 14:06
     * @param $childResources
     * @return static
     */
    public function api($childResources)
    {
        $this->childResources = $childResources;
        return $this;
    }

    public abstract function setHttpClient();
}

