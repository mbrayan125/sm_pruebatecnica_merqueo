<?php

namespace Tests\Feature\Http\Controllers\Simulator;

use App\ProjectElements\AppDispatcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FootballControllerTest extends TestCase
{
    
    /**
     * Test if list of championships are succesful
     *
     * @return void
     */
    public function testSimulationList()
    {
        AppDispatcher::setUpDispatcher("test");
        $response = $this->get('/simulation/list');
        $response->assertSuccessful();
    }
    
    /**
     * Test if list of past championship are succesful
     *
     * @return void
     */
    public function testSimulationView()
    {
        $response = $this->get('/simulation/view/1');
        $response->assertSuccessful();
    }
}
