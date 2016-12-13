<?php

namespace Box\Auth;

use Firebase\JWT\JWT;
use GuzzleHttp\Client as GuzzleClient;
use Box\Enums\GrantType;
use Box\Services\Folders\FolderService;
use Box\Services\Files\FileService;

class AppAuth
{

    private $token_info;

    protected $folder_service = null;

    protected $file_service = null;

    public function __construct($app_auth_info)
    {
        $this->guzzle_client = new GuzzleClient();

        $this->pass_phrase = '';

        foreach ($app_auth_info as $key => $value) {
            $this->$key = $value;
        }
    }

    public function authenticate(JWTClaim $claim)
    {
        // $key Has to be the key file handler opened using openssl method
        $key = openssl_get_privatekey("file://".getcwd().'/' . $this->private_key, $this->pass_phrase);

        if ($key === false) {
            throw new \Exception('Couldnt read key from "' . "file://".getcwd().'/' . $this->private_key . '" with pass phrase "' . $this->pass_phrase . '"');
        }

        $token = $claim->toArray();

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

        return $this->getTokenInfo();
    }

    public function getTokenInfo()
    {
        // TODO: Validate if token is expired and then send
        return $this->token_info;
    }

    public function getFileService($force_new_instance = false)
    {
        if (is_null($this->file_service) || $force_new_instance) {
            $this->file_service = new FileService($this);
        }

        return $this->file_service;
    }

    public function getFolderService($force_new_instance = false)
    {
        if (is_null($this->folder_service) || $force_new_instance) {
            $this->folder_service = new FolderService($this);
        }

        return $this->folder_service;
    }
}
