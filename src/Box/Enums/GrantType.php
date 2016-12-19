<?php

namespace Linkstreet\Box\Enums;

/**
 * Class GrantType
 * @package Linkstreet\Box\Enums
 */
abstract class GrantType
{
    /**
     * JWT.
     */
    const JWT = 'urn:ietf:params:oauth:grant-type:jwt-bearer';
    /**
     * AUTHORIZATION_CODE.
     */
    const AUTHORIZATION_CODE = 'authorization_code';
    /**
     * REFRESH_TOKEN.
     */
    const REFRESH_TOKEN = 'refresh_token';
    /**
     * CLIENT_CREDENTIALS.
     */
    const CLIENT_CREDENTIALS = 'client_credentials';
}
