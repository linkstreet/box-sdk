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
        $json_object = json_decode($folder_service->getFolderInfo()->getBody()->getContents());

        $this->assertObjectHasAttribute('id', $json_object);
        $this->assertEquals('0', $json_object->id);
    }

}