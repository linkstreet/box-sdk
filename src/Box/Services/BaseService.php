<?php

namespace Box\Services;

use GuzzleHttp\Client as GuzzleClient;
use Box\Auth\AppAuth;

class BaseService
{
    protected $guzzle_client;

    protected $app_auth;

    public function __construct(AppAuth $app_auth)
    {
        $this->guzzle_client = new GuzzleClient();

        $this->app_auth = $app_auth;
    }

    protected function getAuthHeaders()
    {
        return [
            "Authorization" => "Bearer " . $this->app_auth->getTokenInfo()->access_token
        ];
    }
}
