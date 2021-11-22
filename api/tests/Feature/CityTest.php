<?php
namespace Tests\Feature\SysAdmin;

use Illuminate\Http\Response;
use Tests\TestCase;

class CityTest extends TestCase
{

    public function testGetCityDetails(){
        $response = $this->getJson("api/details/Japan", []);
        $this->debug($response);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'message'
        ]);
    }


}
