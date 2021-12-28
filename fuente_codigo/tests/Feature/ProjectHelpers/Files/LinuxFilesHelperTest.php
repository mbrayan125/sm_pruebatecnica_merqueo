<?php

namespace Tests\Feature\ProjectHelpers\Files;

use App\ProjectHelpers\Files\LinuxFilesHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LinuxFilesHelperTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $linuxFilesHelper = new LinuxFilesHelper();
        $expectedRoute = "/app/new/branding/php.php";
        $resultClean = $linuxFilesHelper::createRoute("app//new", "branding/", "php.php");
        $this->assertEquals($expectedRoute, $resultClean);
    }
}
