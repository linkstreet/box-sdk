<?php

namespace Box\Enums;

/**
 * Class BoxAccessPoints
 * @package Box\Enums
 */
abstract class BoxAccessPoints
{

    /**
     * URL Separator
     */
    const URL_SEPARATOR = "/";
    
    /**
     * API base url
     */
    const API_BASE_URL = "https://api.box.com";
    
    /**
     * API base url
     */
    const API_UPLOAD_URL = "https://upload.box.com/api";

    /**
     * Access point version
     */
    const API_VERSION = "/2.0";

    /**
     * oAuth2 token end point.
     */
    const OAUTH2_TOKEN = SELF::API_BASE_URL . "/oauth2/token";

    /**
     * Folder info API
     */
    const FOLDER_INFO = SELF::API_BASE_URL . SELF::API_VERSION . "/folders";

    /**
     * File upload API
     */
    const FILE_UPLOAD = SELF::API_UPLOAD_URL . SELF::API_VERSION . "/files/content";

    /**
     * File upload preflight API
     */
    const FILE_UPLOAD_PREFLIGHT = SELF::API_BASE_URL . SELF::API_VERSION . "/files/content";

    /**
     * Create folder API
     */
    const CREATE_FOLDER = SELF::API_BASE_URL . SELF::API_VERSION . "/folders";

    /**
     * File get embeedlink API
     */
    const FILE_EMBED_URL = SELF::APIBASEURL . SELF::APIVERSION . "/files";
}
