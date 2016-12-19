<?php

namespace Linkstreet\Box\Services;

use Linkstreet\Box\Auth\AppAuth;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class BaseService
 * @package Linkstreet\Box\Services
 */
class BaseService
{
    /**
     * @var GuzzleClient
     */
    protected $guzzle_client;

    /**
     * @var AppAuth
     */
    protected $app_auth;

    /**
     * BaseService constructor.
     * @param AppAuth $app_auth
     */
    public function __construct(AppAuth $app_auth)
    {
        $this->guzzle_client = new GuzzleClient();

        $this->app_auth = $app_auth;
    }

    /**
     * @return array
     */
    protected function getAuthHeaders()
    {
        return [
            "Authorization" => "Bearer " . $this->app_auth->getTokenInfo()->access_token
        ];
    }
}
