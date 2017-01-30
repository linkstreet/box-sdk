## Box Api v2.0 SDK under development.

So far this SDK has only machine to machine authentication mechanism and the following functionality

#### Folders
1. Get folder info
2. Get folder items
3. Create folder
4. Delete folder
5. Get trashed items
6. Destroy trashed folder
7. Restore the trashed folder

#### Files
1. Upload file
2. Upload Pre flight - This API is used to check if the metadata supplied is valid or not.
3. Get embed URL
4. Delete a file (soft delete - Moves to trash)
5. Destroy trashed file
6. Restore the trashed file

### Usage

```php
use Linkstreet\Box;
use Linkstreet\Box\Enums\SubscriptionType;

$box_sdk = new Box(['client_id' => "", "client_secret" => ""]);

$app_auth_info = [
    "key_id" => "key id from box app",
    "private_key" => "path to private key",
    "pass_phrase" => "passphrase", // Can be empty
    "subscription_type" => SubscriptionType::ENTERPRISE or SubscriptionType::USER,
    "id" => "enterprise id or user id"
];

// Authenticates with box server and returns app_auth instance.
// Throws `GuzzleHttp\Exception\ClientException` on failure 
$app_auth_instance = $box_sdk->getAppAuthClient($app_auth_info);
```

#### Folder Service
1. To get the service

    ```php
    $folder_service = $app_auth_instance->getFolderService();
    ```

2. Methods available in `$folder_service`

    ```
    1. getFolderInfo($folder_id); // Defaults to root folder (id = 0)
    2. getFolderItems($folder_id = 0, $fields = [], $limit = 100, $offset = 0); // Defaults to root folder (id = 0)
    3. create($folder_name, $parent_folder_id = 0)
    4. delete($folder_id, $recursive = false, $e_tag = null)
    5. getTrashedItems()
    6. destroyTrashedFolder($folder_id)
    7. restore($trashed_folder_id, $new_name = null, $parent_folder_id = null)
    ```

#### File Service
1. To get the service

    ```php
    $file_service = $app_auth_instance->getFileService();
    ```

2. Methods available in `$file_service`

    ```
    1. uploadPreFlight($file_path = "", $folder_id = 0, $filename = null); // If filename is null, file name will be derived from actual file name.
    2. upload($file_path = "", $folder_id = 0, $filename = null); // If filename is null, file name will be derived from actual file name.
    3. getEmbedUrl($file_id)
    4. delete($file_id)
    5. destroyTrashedFile($file_id)
    6. restore($trashed_file_id, $new_name = null, $parent_folder_id = null)
    ```
          
        
##### NOTE:

1. All APIs return `\GuzzleHttp\Psr7\Response` (to get the body use `$response->getBody()->getContents()`. Refer [Guzzle HTTP Messages](http://docs.guzzlephp.org/en/latest/psr7.html#responses)) except `getEmbedUrl($file_id)` which returns string. 
2. Guzzle related exceptions and its documentation can be found in [Guzzle Docs](http://docs.guzzlephp.org/en/latest/quickstart.html#exceptions)