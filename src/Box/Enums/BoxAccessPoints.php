<?php

namespace Linkstreet\Box\Enums;

/**
 * Class BoxAccessPoints
 * @package Linkstreet\Box\Enums
 */
abstract class BoxAccessPoints
{

    // TODO: Revert this commit for php version 5.6

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
    const OAUTH2_TOKEN = "https://api.box.com/oauth2/token";

    /**
     * Base folder API
     */
    const BASE_FOLDER_URL = "https://api.box.com/2.0/folders";

    /**
     * File upload API
     */
    const FILE_UPLOAD = "https://upload.box.com/api/2.0/files/content";

    /**
     * File upload pre-flight API
     */
    const FILE_UPLOAD_PREFLIGHT = "https://api.box.com/2.0/files/content";

    /**
     * Base file API
     */
    const BASE_FILE_URL = "https://api.box.com/2.0/files";
}
