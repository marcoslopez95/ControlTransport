<?php

namespace Tests\Feature;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DriverTest extends TestCase
{
    use RefreshDatabase;

    private function createToken(){
        $user = User::create([
            'first_name'    => 'Admin',
            'last_name'     => 'Admin',
            'email'         => 'admin@controltransport.com',
            'password'      => 'admin123admin',
            'role_id'       => 1
        ]);

        $token = $user->createToken('login')->plainTextToken;
        $pos = strpos($token,'|');
        $token = substr($token,$pos+1);
        return $token;
    }

    public function test_driver_can_be_created(){
        $this->withoutExceptionHandling();

        $response = $this->postJson('/api/drivers',[
            'first_name' => 'Marcos',
            'last_name'  => 'López',
            'ci'         => '123123123'
        ],[
            'Authorization' => 'Bearer '.self::createToken()
        ]);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'success' => true
                ]);$response->assertStatus(201)
                ->assertJsonFragment([
                    'success' => true
                ]);

        $partner = Driver::first();

        $this->assertCount(1,Driver::all());
        $this->assertEquals($partner->first_name,'Marcos');
    }

    public function test_list_can_be_retrieved(){
        $this->withoutExceptionHandling();
        Driver::factory(5)->create();

        $response = $this->getJson('api/drivers',[
            'Authorization' => 'Bearer '.self::createToken()
        ]);
        $response->assertOk()
            ->assertJsonFragment([
            'success' => true
        ]);

        $drivers = Driver::all();

        $response->assertJsonPath('data',$drivers->toArray());
    }

    public function test_driver_can_be_show(){
        $this->withoutExceptionHandling();

        Driver::factory(1)->create();
        $partner = Driver::first();
        $response = $this->get('api/drivers/'.$partner->id,[
            'Authorization' => 'Bearer '.self::createToken()
        ]);
        $response->assertOk()
            ->assertJsonFragment([
            'success' => true
        ]);

        $response->assertJsonPath('data',$partner->toArray());
    }

    public function test_driver_can_be_updated(){
        $this->withoutExceptionHandling();
        $partner = Driver::factory(1)->create();
        $response = $this->putJson('api/drivers/'.$partner[0]->id,[
            'first_name' => 'Marcos',
            'last_name'  => 'Lopez',
            'ci'         => '123'
        ],[
            'Authorization' => 'Bearer '.self::createToken()
        ]);
        $response->assertStatus(205)
                ->assertJsonFragment([
                    'success' => true
                ]);

        $partner_bd = Driver::first();

        $this->assertEquals($partner_bd->first_name, 'Marcos');
        $this->assertEquals($partner_bd->last_name, 'Lopez');
        $this->assertEquals($partner_bd->ci, '123');
    }

    public function test_driver_can_be_deleted(){
        $this->withoutExceptionHandling();
        $partner = Driver::factory(1)->create();
        $id = $partner[0]->id;
        $response = $this->deleteJson('api/drivers/'.$id,[],[
            'Authorization' => 'Bearer '.self::createToken()
        ]);
        $response->assertStatus(202)
                ->assertJsonFragment([
                    'success' => true
                ]);

        $partner_bd = Driver::find($id);

        $this->assertEquals($partner_bd, '');
    }

    public function test_fields_required_to_save_driver(){
        //$this->withoutExceptionHandling();
        $response = $this->postJson('api/drivers',[],[
            'Authorization' => 'Bearer '.self::createToken()
        ]);

        $response->assertStatus(422)
                ->assertJsonFragment([
                    'success' => false
                ]);
    }
    public function test_unauthorized(){
        $response = $this->getJson('api/drivers');
        $response->assertStatus(401);
    }

    public function test_unique_ci_in_drivers(){
        Driver::create([
            'first_name' => 'Nombre',
            'last_name'  => 'Apellido',
            'ci'         => '123'
        ]);

        $response = $this->postJson('api/drivers',[
            'first_name'    => 'Marcos',
            'last_name'     => 'Lopez',
            'ci'            => '123'
        ],[
            'Authorization' => 'Bearer '.self::createToken()
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'success' => false
            ]);
    }

    public function test_fields_required_to_update_driver(){
        //$this->withoutExceptionHandling();
        Driver::create([
            'first_name' => 'Nombre',
            'last_name'  => 'Apellido',
            'ci'         => '123'
        ]);
        $response = $this->putJson('api/drivers/1',[],[
            'Authorization' => 'Bearer '.self::createToken()
        ]);

        $response->assertStatus(422)
                ->assertJsonFragment([
                    'success' => false
                ]);
    }

    public function test_unique_ci_in_update_driver(){
        Driver::create([
            'first_name' => 'Nombre',
            'last_name'  => 'Apellido',
            'ci'         => '123'
        ]);
        Driver::create([
            'first_name' => 'Nombre',
            'last_name'  => 'Apellido',
            'ci'         => '312'
        ]);

        $response = $this->putJson('api/drivers/2',[
            'ci'         => '123'
        ],[
            'Authorization' => 'Bearer '.self::createToken()
        ]);
        $response->assertStatus(422)
            ->assertJsonFragment([
                'success' => false
            ]);
    }
}
