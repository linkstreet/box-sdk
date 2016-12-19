<?php
namespace Tests;

use Symfony\Component\Yaml\Yaml;
use Linkstreet\Box\Box;
use Linkstreet\Box\Auth\AppAuth;

/**
 * Class Base
 * @package Tests
 */
class Base extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Box
     */
    protected $box_sdk;
    /**
     * @var AppAuth
     */
    protected $app_auth;
    /**
     * @var string The folder in which the tests will be run. (This folder will be cleaned after tests are executed)
     */
    protected $folder;

    /**
     * @var mixed
     */
    private $credentials;

    /**
     * Base constructor.
     */
    public function __construct()
    {
        // Read the credentials from credentials.yaml file. Create a file before running any tests.
        $this->credentials = Yaml::parse(file_get_contents('secrets/credentials.yaml'));

        $this->box_sdk = new Box($this->credentials["sdk_info"]);
        $this->app_auth = $this->box_sdk->getAppAuthClient($this->credentials["app_auth_info"]);
    }

    /**
     * Test if correct instance is in app_auth variable
     */
    public function testAppAuthAuthenticate()
    {
        $this->assertInstanceOf(AppAuth::class, $this->app_auth);
    }
}