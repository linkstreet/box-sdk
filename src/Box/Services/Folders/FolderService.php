<?php

namespace Box\Services\Folders;

use Box\Services\BaseService;
use Box\Enums\BoxAccessPoints;
use Box\Auth\AppAuth;

class FolderService extends BaseService
{

    public function __construct(AppAuth $app_auth)
    {
        parent::__construct($app_auth);
    }

    public function getFolderInfo($folder_id = 0)
    {
        // Throws exception on 4XX response code
        $response = $this->guzzle_client->request(
            'GET',
            BoxAccessPoints::FOLDERINFO . $folder_id,
            [
                'headers' => [
                    "Authorization" => "Bearer " . $this->app_auth->getTokenInfo()->access_token
                ]
            ]
        );

        // TODO: Marshall the response and return folder object.
    }
}
