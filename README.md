## Box Api v2.0 SDK under development.

So far this SDK has only machine to machine authentication mechanism and the following functionality

#### Folders
1. Get folder info
2. Create folder

#### Files
1. Upload file
2. Upload Pre flight - This API is used to check if the metadata supplied is valid or not.
3. Get embed URL

### Usage

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

#### Folder Service
1. To get the service

        $folder_service = $app_auth_instance->getFolderService();

2. Methods available in `$folder_service`
    
        1. getFolderInfo($folder_id); // Defaults to root folder (id = 0)
        2. createFolder($folder_name, $parent_folder_id = 0)

#### File Service
1. To get the service

        $file_service = $app_auth_instance->getFileService();

2. Methods available in `$file_service`
    
        1. uploadPreFlight($file_path = "", $folder_id = 0);
        2. uploadFile($file_path = "", $folder_id = 0);
        3. getEmbedUrl($file_id)