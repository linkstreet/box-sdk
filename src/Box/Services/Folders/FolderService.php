<?php

namespace Linkstreet\Box\Services\Folders;

use Linkstreet\Box\Auth\AppAuth;
use Linkstreet\Box\Enums\BoxAccessPoints as BAP;
use Linkstreet\Box\Services\BaseService;
use Webmozart\Assert\Assert;

/**
 * Class FolderService
 * @package Linkstreet\Box\Services\Folders
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
     * @param $folder_id integer id of the folder.
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Response
     */
    public function getFolderInfo($folder_id = 0)
    {
        Assert::integerish($folder_id, "The folder id must be an integer. Got: %s");

        return $this->guzzle_client->request(
            'GET',
            BAP::BASE_FOLDER_URL . BAP::URL_SEPARATOR . $folder_id,
            [
                'headers' => [
                    "Authorization" => "Bearer " . $this->app_auth->getTokenInfo()->access_token
                ]
            ]
        );
    }

    /**
     * @param $folder_name string folder name to be created
     * @param $parent_folder_id integer id of the parent folder. Defaults to root folder.
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Response
     */
    public function create($folder_name, $parent_folder_id = 0)
    {
        $folder_name = trim($folder_name);
        Assert::stringNotEmpty($folder_name, "The folder name must be string and not empty. Got: %s");
        Assert::maxLength($folder_name, 255, "The folder name must be shorter than 255 chars. Got: %s");

        // Throws exception on 4XX response code
        return $this->guzzle_client->request(
            'POST',
            BAP::BASE_FOLDER_URL,
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

    /**
     * Method to get items inside a folder. Same info can be found inside `getFolderInfo` method as well.
     * @param $folder_id integer Id of the folder for which the items has to be listed.
     * @param $fields array of fields which has to be included in response
     * @param $limit integer Variable to limit the response items
     * @param $offset integer Variable to offset the response items
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Response
     */
    public function getFolderItems($folder_id = 0, $fields = [], $limit = 100, $offset = 0)
    {
        Assert::integerish($folder_id, "The folder id must be an integer. Got: %s");
        Assert::integerish($limit, "The limit must be an integer. Got: %s");
        Assert::integerish($offset, "The offset must be an integer. Got: %s");

        $query_fields = array(
          'fields' => array(),
        );

        if (!empty($fields)) {
          foreach ($fields as $field) {
              if (trim($field)) {
                  $query_fields['fields'][] = trim($field);
              }
          }
        } else {
          unset($query_fields['fields']);
        }

        $query_fields['limit'] = $limit;
        $query_fields['offset'] = $offset;

        $query = http_build_query($query_fields);
        return $this->guzzle_client->request(
            'GET',
            BAP::BASE_FOLDER_URL . BAP::URL_SEPARATOR . $folder_id . BAP::URL_SEPARATOR . "items" . '?' . $query,
            [
                'headers' => [
                    "Authorization" => "Bearer " . $this->app_auth->getTokenInfo()->access_token
                ]
            ]
        );
    }

    /**
     * Method to delete folder from box
     * @param $folder_id integer ID of the folder which has to be deleted
     * @param $recursive bool
     * @param $e_tag null
     * @return \GuzzleHttp\Psr7\Response
     */
    public function delete($folder_id, $recursive = false, $e_tag = null)
    {
        Assert::integer($folder_id, "The folder id must be an integer. Got: %s");

        $query = "";
        if ($recursive === true) {
            $query = "?recursive=true";
        }

        $headers = $this->getAuthHeaders();

        if (!is_null($e_tag)) {
            $headers = array_merge($headers, ["If-Match" => $e_tag]);
        }

        return $this->guzzle_client->request(
            'DELETE',
            BAP::BASE_FOLDER_URL . BAP::URL_SEPARATOR . $folder_id . $query,
            [
                "headers" => $headers
            ]
        );
    }

    /**
     * Method to list all the trashed folders and files.
     * @return \GuzzleHttp\Psr7\Response
     */
    public function getTrashedItems()
    {
        return $this->guzzle_client->request(
            'GET',
            BAP::BASE_FOLDER_URL . BAP::URL_SEPARATOR . "trash" . BAP::URL_SEPARATOR . "items",
            [
                'headers' => [
                    "Authorization" => "Bearer " . $this->app_auth->getTokenInfo()->access_token
                ]
            ]
        );
    }

    /**
     * Method to remove the folder permanently from trash.
     * Returns 204 on success and 404 on file not found in trash
     * @param $folder_id integer ID of the folder which has to be deleted
     * @return \GuzzleHttp\Psr7\Response
     */
    public function destroyTrashedFolder($folder_id)
    {
        Assert::integer($folder_id, "The folder id must be an integer. Got: %s");

        return $this->guzzle_client->request(
            'DELETE',
            BAP::BASE_FOLDER_URL . BAP::URL_SEPARATOR . $folder_id  . BAP::URL_SEPARATOR . "trash",
            [
                "headers" => $this->getAuthHeaders()
            ]
        );
    }

    /**
     * Method to restore the folder from trash.
     * CAUTION : Parent id is only taken into consideration if the current parent folder is deleted or
     * a folder (or a file) with same name is already present in parent folder.
     * 201 on success
     * 403 on access denied
     * 405 on not found in trash
     * 409 on parent folder already having a folder or a file with same name
     * @param $trashed_folder_id integer ID of the trashed folder
     * @param $new_name null|string Renaming the folder on the fly.
     * @param $parent_folder_id null|integer Parent folder ID for which the folder will be restored to
     * @return \GuzzleHttp\Psr7\Response
     */
    public function restore($trashed_folder_id, $new_name = null, $parent_folder_id = null)
    {
        Assert::integer($trashed_folder_id, "The folder id must be integer. Got: %s");

        $payload = [];

        if (!is_null($parent_folder_id)) {
            Assert::integer($parent_folder_id, "The parent id must be integer. Got: %s");
            $payload['parent'] = [
                "id" => (string)$parent_folder_id
            ];
        }

        if (!is_null($new_name)) {
            $payload['name'] = $new_name;
        }

        // Throws exception on 4XX response code
        return $this->guzzle_client->request(
            'POST',
            BAP::BASE_FOLDER_URL . BAP::URL_SEPARATOR . $trashed_folder_id,
            [
                "json" => $payload,
                "headers" => $this->getAuthHeaders()
            ]
        );
    }
}
