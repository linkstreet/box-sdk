<?php

namespace Linkstreet\Box;

use Linkstreet\Box\Auth\AppAuth;
use Linkstreet\Box\Auth\JWTClaim;
use Linkstreet\Box\Enums\SubscriptionType;
use Webmozart\Assert\Assert;

/**
 * Class Box
 * @package Box
 */
class Box
{

    /**
     * Variable which holds the app auth instance
     * Linkstreet\Box\Auth\AppAuth
     */
    protected $app_auth;

    /**
     * Variable which holds the jwt claim instance
     * Linkstreet\Box\Auth\JWTClaim
     */
    protected $jwt_claim = null;

    /**
     * Variable which holds client_id and client_secret
     * array
     */
    private $client_info = [];

    /**
     * Validating and initializing client info
     * @param $client_info array
     */
    public function __construct($client_info = [])
    {
        $this->validateClientInfo($client_info);

        $this->client_info = $client_info;
    }

    /**
     * Method to validate the $client_info
     * @param $client_info array
     */
    private function validateClientInfo($client_info)
    {
        Assert::keyExists($client_info, 'client_id', 'Missing client id');
        Assert::keyExists($client_info, 'client_secret', 'Missing client secret');
        Assert::stringNotEmpty($client_info['client_id'], 'The client id must be string and not empty. Got: %s');
        Assert::stringNotEmpty($client_info['client_secret'], 'The client id must be string and not empty. Got: %s');
    }

    /**
     * Method which creates AppAuth client
     * @param $app_auth_info array
     * @return \Linkstreet\Box\Auth\AppAuth
     */
    public function getAppAuthClient($app_auth_info = [])
    {
        $this->validateAppAuthInfo($app_auth_info);

        $this->jwt_claim = $this->createJWTClaim($app_auth_info);

        $this->app_auth = new AppAuth(array_merge($app_auth_info, $this->client_info));

        $this->app_auth->authenticate($this->jwt_claim);

        return $this->app_auth;
    }

    /**
     * Method to validate the $app_auth_info
     * @param $app_auth_info array
     */
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

    /**
     * Method which creates JWTClaim
     * @param $app_auth_info array
     * @param $key_length Integer Defaults to 32
     * @param $expiry_buffer int Should not be more than 60. Defaults to 10
     * @return \Linkstreet\Box\Auth\JWTClaim
     */
    protected function createJWTClaim($app_auth_info, $key_length = 32, $expiry_buffer = 10)
    {
        $unique_key = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($key_length / strlen($x)))), 1, $key_length);

        $config = [
            "iss" => $this->client_info['client_id'],
            "sub" => $app_auth_info['id'],
            "box_sub_type" => $app_auth_info['subscription_type'],
            "jti" => $unique_key,
            "exp" => (time() + (($expiry_buffer <= 60) ? $expiry_buffer : 60))
        ];

        return new JWTClaim($config);
    }
}
