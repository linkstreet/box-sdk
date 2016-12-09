<?php

namespace Box\Enums;

/**
 * Class BoxAccessPoints
 * @package Box\Enums
 */
abstract class BoxAccessPoints
{
    /**
     * API base url
     */
    const APIBASEURL = "https://api.box.com";

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
}
