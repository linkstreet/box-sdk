<?php

namespace Box\Services\Folders;

use Box\Auth\AppAuth;
use Box\Enums\BoxAccessPoints;
use Box\Services\BaseService;
use Webmozart\Assert\Assert;

/**
 * Class FolderService
 * @package Box\Services\Folders
 */
class FolderService extends BaseService
{

    /**
     * FolderService constructor.
     * @param AppAuth $app_auth
     */
    public function __construct(AppAuth $app_auth)
    {
        parent::__construct($app_auth);
    }

    /**
     * Method to get folder info. Defaults to 0 which is the root folder.
     * @param $folder_id int id of the folder.
     * @return \GuzzleHttp\Psr7\Response
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

    /**
     * @param $folder_name string folder name to be created
     * @param $parent_folder_id int id of the parent folder. Defaults to root folder.
     * @return \GuzzleHttp\Psr7\Response
     */
    public function createFolder($folder_name, $parent_folder_id = 0)
    {
        $folder_name = trim($folder_name);
        Assert::stringNotEmpty($folder_name, "The folder name must be string and not empty. Got: %s");
        Assert::maxLength($folder_name, 255, "The folder name must be shorter than 255 chars. Got: %s");

        // Throws exception on 4XX response code
        return $this->guzzle_client->request(
            'POST',
            BoxAccessPoints::CREATE_FOLDER,
            [
                "json" => [
                    "name" => $folder_name,
                    "parent" => [
                        "id" => (string)$parent_folder_id
                    ]
                ],
                "headers" => $this->getAuthHeaders()
            ]
        );
    }
}
