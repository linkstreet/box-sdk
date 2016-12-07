<?php

namespace Box\Auth;

use Firebase\JWT\JWT;
use GuzzleHttp\Client as GuzzleClient;
use Box\Enums\GrantType;

class AppAuth
{

    private $token_info;

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

        $this->token_info['issued_time'] = time();

        return $this->token_info;
    }



    public function getUserService()
    {
        // TODO: Implement the service
    }

    public function getFileService()
    {
        // TODO: Implement the service
    }

    public function getFolderService()
    {
        // TODO: Implement the service
    }

    public function getMetadataService()
    {
        // TODO: Implement the service
    }

    public function getWebLinksService()
    {
        // TODO: Implement the service
    }

    public function getWatermarkingService()
    {
        // TODO: Implement the service
    }

    public function getCollectionsService()
    {
        // TODO: Implement the service
    }

    public function getSearchService()
    {
        // TODO: Implement the service
    }

    public function getCollaborationsService()
    {
        // TODO: Implement the service
    }

    public function getSharedItemsService()
    {
        // TODO: Implement the service
    }

    public function getCommentsService()
    {
        // TODO: Implement the service
    }

    public function getTasksService()
    {
        // TODO: Implement the service
    }

    public function getEventsService()
    {
        // TODO: Implement the service
    }

    public function getWebhooksV2Service()
    {
        // TODO: Implement the service
    }

    public function getUsersService()
    {
        // TODO: Implement the service
    }

    public function getGroupsService()
    {
        // TODO: Implement the service
    }

    public function getDevicesService()
    {
        // TODO: Implement the service
    }

    public function getRetentionPoliciesService()
    {
        // TODO: Implement the service
    }

    public function getLegalHoldsPolicyService()
    {
        // TODO: Implement the service
    }
}
