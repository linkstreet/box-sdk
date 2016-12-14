<?php
namespace Tests\Services;

use Tests\Base;

/**
 * Class FolderServiceTest
 * @package Tests\Services
 */
class FolderServiceTest extends Base
{
    /**
     * Test if getFolderInfo service is returning correct info
     */
    public function testGetFolderInfo()
    {
        $folder_service = $this->app_auth->getFolderService();
        $response = $folder_service->getFolderInfo();

        $json_object = json_decode($response->getBody()->getContents());

        $this->assertObjectHasAttribute('id', $json_object);
        $this->assertEquals('0', $json_object->id);
    }

}