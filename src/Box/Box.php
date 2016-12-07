<?php

namespace Box;

use Webmozart\Assert\Assert;
use Box\Enums\SubscriptionType;
use Box\Auth\JWTClaim;
use Box\Auth\AppAuth;

class Box
{

    protected $app_auth;

    protected $jwt_claim = null;

    private $client_info = [];
    
    public function __construct($client_info = [])
    {
        $this->validateClientInfo($client_info);

        $this->client_info = $client_info;
    }

    public function getAppAuthClient($app_auth_info = [])
    {
        $this->validateAppAuthInfo($app_auth_info);

        $this->claim = $this->createJWTClaim($app_auth_info);

        $this->app_auth = new AppAuth(array_merge($app_auth_info, $this->client_info));

        $this->app_auth->authenticate($this->claim);

        return $this->app_auth;
    }

    protected function createJWTClaim($app_auth_info, $key_length = 32, $expiry_buffer = 10)
    {
        $unique_key = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($key_length/strlen($x)))), 1, $key_length);

        $config = [
            "iss" => $this->client_info['client_id'],
            "sub" => $app_auth_info['id'],
            "box_sub_type" => $app_auth_info['subscription_type'],
            "jti" => $unique_key,
            "exp" => time() + (($expiry_buffer <= 60) ?: 60)
        ];

        return new JWTClaim($config);
    }

    private function validateClientInfo($client_info)
    {
        Assert::keyExists($client_info, 'client_id', 'Missing client id');
        Assert::keyExists($client_info, 'client_secret', 'Missing client secret');
        Assert::stringNotEmpty($client_info['client_id'], 'The client id must be string and not empty. Got: %s');
        Assert::stringNotEmpty($client_info['client_secret'], 'The client id must be string and not empty. Got: %s');
    }

    private function validateAppAuthInfo($app_auth_info)
    {
        Assert::keyExists($app_auth_info, 'key_id', 'Missing key id');
        Assert::keyExists($app_auth_info, 'private_key', 'Missing private key path');
        Assert::keyExists($app_auth_info, 'id', 'Missing id. (id can be enterprise id or user id)');
        Assert::keyExists($app_auth_info, 'subscription_type', 'Missing box subscription type. (Options are `enterprise` and `user`)');

        Assert::notEmpty($app_auth_info['key_id'], 'Missing key id');
        Assert::notEmpty($app_auth_info['private_key'], 'Missing private key path');
        Assert::notEmpty($app_auth_info['id'], 'Missing id. (id can be enterprise id or user id)');
        Assert::notEmpty($app_auth_info['subscription_type'], 'Missing box subscription type. (Options are `enterprise` and `user`)');

        Assert::oneOf($app_auth_info['subscription_type'], [SubscriptionType::ENTERPRISE, SubscriptionType::USER], 'Wrong box subscription type. (Options are `enterprise` and `user`)');
    }
}
