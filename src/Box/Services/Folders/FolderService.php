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

    /**
     * Method to get folder info. Defaults to 0 which is the root folder.
     * @param $folder_id id of the folder.
     * @return GuzzleHttp\Psr7\Response
     */
    public function getFolderInfo($folder_id = 0)
    {
        // Throws exception on 4XX response code
        return $this->guzzle_client->request(
            'GET',
            BoxAccessPoints::FOLDER_INFO . BoxAccessPoints::URL_SEPARATOR . $folder_id,
            [
                'headers' => [
                    "Authorization" => "Bearer " . $this->app_auth->getTokenInfo()->access_token
                ]
            ]
        );
    }
}
