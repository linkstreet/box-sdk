<?php

namespace Linkstreet\Box\Services\Files;

use Linkstreet\Box\Auth\AppAuth;
use Linkstreet\Box\Enums\BoxAccessPoints as BAP;
use Linkstreet\Box\Enums\ExceptionMessages;
use Linkstreet\Box\Exceptions\Files\FileNotFoundException;
use Linkstreet\Box\Services\BaseService;
use Webmozart\Assert\Assert;

/**
 * Class FileService
 * @package Linkstreet\Box\Services\Files
 */
class FileService extends BaseService
{

    /**
     * FileService constructor.
     * @param AppAuth $app_auth
     */
    public function __construct(AppAuth $app_auth)
    {
        parent::__construct($app_auth);
    }

    /**
     * @param $file_path string
     * @param $folder_id integer
     * @param $filename string
     * @return \GuzzleHttp\Psr7\Response
     */
    public function uploadPreFlight($file_path = "", $folder_id = 0, $filename = null)
    {
        Assert::integerish($folder_id, "The folder id must be an integer. Got: %s");

        $this->readFile($file_path);

        $filename = ((!is_null($filename)) ? $filename : basename($file_path));

        // Throws exception on 4XX response code
        return $this->guzzle_client->request(
            'OPTIONS',
            BAP::FILE_UPLOAD_PREFLIGHT,
            [
                "json" => [
                    "name" => $filename,
                    "parent" => [
                        "id" => (string)$folder_id
                    ],
                    "size" => filesize($file_path)
                ],
                "headers" => $this->getAuthHeaders()
            ]
        );
    }

    /**
     * Returns the handle of fopen
     * @param $file_path
     * @return resource
     * @throws FileNotFoundException
     */
    private function readFile($file_path)
    {
        if (!file_exists($file_path) || (($handle = fopen($file_path, 'r')) === false)) {
            throw new FileNotFoundException(ExceptionMessages::FILE_NOT_FOUND . " (file : " . $file_path . ")");
        }

        return $handle;
    }

    /**
     * Method to upload the file. By default it uploads to the root folder which is id 0
     * @param string $file_path
     * @param int $folder_id
     * @param string $filename
     * @return \GuzzleHttp\Psr7\Response
     */
    public function upload($file_path = "", $folder_id = 0, $filename = null)
    {
        Assert::integerish($folder_id, "The folder id must be an integer. Got: %s");

        $handle = $this->readFile($file_path);

        $filename = ((!is_null($filename)) ? $filename : basename($file_path));

        return $this->guzzle_client->request(
            'POST',
            BAP::FILE_UPLOAD,
            [
                "multipart" => [
                    [
                        "name" => "attributes",
                        "contents" => json_encode([
                            "name" => $filename,
                            "parent" => [
                                "id" => $folder_id
                            ]
                        ])
                    ],
                    [
                        "name" => "file",
                        "contents" => $handle
                    ]
                ],
                "headers" => $this->getAuthHeaders()
            ]
        );
    }

    /**
     * Method to get embed url of an uploaded file
     * @param $file_id integer
     * @return String Embed url which has to be added to i-frame source
     */
    public function getEmbedUrl($file_id)
    {
        Assert::integer($file_id, 'file id has to be an integer. Got: %s');

        $response = $this->guzzle_client->request(
            'GET',
            BAP::BASE_FILE_URL . BAP::URL_SEPARATOR . $file_id . "?fields=expiring_embed_link",
            [
                "headers" => $this->getAuthHeaders()
            ]
        );

        $response = json_decode($response->getBody()->getContents());

        return $response->expiring_embed_link->url;
    }

    /**
     * Method to move a file to trash
     * @param $file_id integer ID of the file to be moved
     * @param $e_tag string E-tag of the uploaded file. This param is to prevent accidental delete in race conditions
     * @return \GuzzleHttp\Psr7\Response
     */
    public function delete($file_id, $e_tag = null)
    {
        Assert::integer($file_id, 'file id has to be an integer. Got: %s');

        $headers = $this->getAuthHeaders();

        if (!is_null($e_tag)) {
            $headers = array_merge($headers, ["If-Match" => $e_tag]);
        }

        return $this->guzzle_client->request(
            'DELETE',
            BAP::BASE_FILE_URL . BAP::URL_SEPARATOR . $file_id,
            [
                "headers" => $headers
            ]
        );
    }

    /**
     * Method to remove the file permanently from trash
     * @param $file_id integer ID of the file which has to be permanently deleted
     * @return \GuzzleHttp\Psr7\Response
     */
    public function destroyTrashedFile($file_id)
    {
        Assert::integer($file_id, 'file id has to be an integer. Got: %s');

        return $this->guzzle_client->request(
            'DELETE',
            BAP::BASE_FILE_URL . BAP::URL_SEPARATOR . $file_id . BAP::URL_SEPARATOR . "trash",
            [
                "headers" => $this->getAuthHeaders()
            ]
        );
    }

    /**
     * Method to restore the file from trash.
     * CAUTION : Parent id is only taken into consideration if the current parent folder is deleted or
     * a folder (or a file) with same name is already there in parent folder.
     * 201 on success
     * 403 on access denied
     * 404 on file not found in trash
     * 409 on parent folder already having a folder or a file with same name
     * @param $trashed_file_id integer ID of the trashed file
     * @param $new_name null|string Renaming the file on the fly.
     * @param $parent_folder_id null|integer Parent folder ID for which the file will be restored to
     * @return \GuzzleHttp\Psr7\Response
     */
    public function restore($trashed_file_id, $new_name = null, $parent_folder_id = null)
    {
        Assert::integer($trashed_file_id, "The file id must be integer. Got: %s");

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
            BAP::BASE_FILE_URL . BAP::URL_SEPARATOR . $trashed_file_id,
            [
                "json" => $payload,
                "headers" => $this->getAuthHeaders()
            ]
        );
    }
}
