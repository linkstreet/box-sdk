<?php

namespace Linkstreet\Box\Auth;

use Linkstreet\Box\Enums\GrantType;
use Linkstreet\Box\Services\Files\FileService;
use Linkstreet\Box\Services\Folders\FolderService;
use Firebase\JWT\JWT;
use GuzzleHttp\Client as GuzzleClient;

/**
 * Class AppAuth
 * @package Linkstreet\Box\Auth
 */
class AppAuth
{

    /**
     * @var FolderService
     */
    protected $folder_service = null;
    /**
     * @var FileService
     */
    protected $file_service = null;
    /**
     * @var
     */
    private $token_info;

    /**
     * AppAuth constructor.
     * @param $app_auth_info
     */
    public function __construct($app_auth_info)
    {
        $this->guzzle_client = new GuzzleClient();

        $this->pass_phrase = '';

        foreach ($app_auth_info as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @param JWTClaim $claim
     * @return mixed
     * @throws \Exception
     */
    public function authenticate(JWTClaim $claim)
    {
        // $key Has to be the key file handler opened using openssl method
        $key = openssl_get_privatekey("file://" . getcwd() . '/' . $this->private_key, $this->pass_phrase);

        if ($key === false) {
            // TODO: Move to separate exception
            throw new \Exception('Could not read key from "' . "file://" . getcwd() . '/' . $this->private_key . '" with pass phrase "' . $this->pass_phrase . '"');
        }

        $token = $claim->toArray();

        // TODO: Move supported algorithms to enum
        $jwt = JWT::encode($token, $key, 'RS256', $this->key_id);

        // Exceptions documentation can be found in http://docs.guzzlephp.org/en/latest/quickstart.html#exceptions
        $res = $this->guzzle_client->request('POST', $claim->aud, [
            'form_params' => [
                'grant_type' => GrantType::JWT,
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
                'assertion' => $jwt
            ]
        ]);

        // TODO: Try using marshaller to convert response data to Token object
        $this->token_info = json_decode($res->getBody()->getContents());

        $this->token_info->issued_time = time();

        return $this->token_info;
    }

    /**
     * @return mixed
     */
    public function getTokenInfo()
    {
        // TODO: Validate if token is expired and then send
        return $this->token_info;
    }

    /**
     * @param bool $force_new_instance
     * @return FileService
     */
    public function getFileService($force_new_instance = false)
    {
        if (is_null($this->file_service) || $force_new_instance) {
            $this->file_service = new FileService($this);
        }

        return $this->file_service;
    }

    /**
     * @param bool $force_new_instance
     * @return FolderService
     */
    public function getFolderService($force_new_instance = false)
    {
        if (is_null($this->folder_service) || $force_new_instance) {
            $this->folder_service = new FolderService($this);
        }

        return $this->folder_service;
    }
}
