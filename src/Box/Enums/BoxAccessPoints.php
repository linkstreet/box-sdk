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
    const URLSEPARATOR = "/";
    
    /**
     * API base url
     */
    const APIBASEURL = "https://api.box.com";
    
    /**
     * API base url
     */
    const APIUPLOADURL = "https://upload.box.com/api";

    /**
     * Access point version
     */
    const APIVERSION = "/2.0";

    /**
     * oAuth2 token end point.
     */
    const OAUTH2TOKEN = SELF::APIBASEURL . "/oauth2/token";

    /**
     * Folder info API
     */
    const FOLDERINFO = SELF::APIBASEURL . SELF::APIVERSION . "/folders";

    /**
     * File upload API
     */
    const FILEUPLOAD = SELF::APIUPLOADURL . SELF::APIVERSION . "/files/content";

    /**
     * File upload preflight API
     */
    const FILEUPLOADPREFLIGHT = SELF::APIBASEURL . SELF::APIVERSION . "/files/content";

    /**
     * File get embeedlink API
     */
    const FILEEMBEDURL = SELF::APIBASEURL . SELF::APIVERSION . "/files";
}
