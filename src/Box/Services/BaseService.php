<?php

namespace Box\Services;

use GuzzleHttp\Client as GuzzleClient;

class BaseService
{
    protected $guzzle_client;

    public function __construct()
    {
        $this->guzzle_client = new GuzzleClient();
    }
}
