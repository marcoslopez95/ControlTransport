<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    private function createToken()
    {
        $user = User::create([
            'first_name'    => 'Admin',
            'last_name'     => 'Admin',
            'email'         => 'admin@controltransport.com',
            'password'      => 'admin123admin',
            'role_id'       => 1
        ]);

        $token = $user->createToken('login')->plainTextToken;
        $pos = strpos($token, '|');
        $token = substr($token, $pos + 1);
        return $token;
    }

    public function test_vehicle_can_be_created()
    {
        $this->withoutExceptionHandling();

        $response = $this->postJson('/api/vehicles', [
            'plate' => 'algo',
            'num_control' => 'algo',
            'description' => 'algo',
            'status' => 'Operativo',
        ], [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'success' => true
            ]);

        $partner = Vehicle::first();

        $this->assertCount(1, Vehicle::all());
        $this->assertEquals($partner->plate, 'algo');
        $this->assertEquals($partner->num_control, 'algo');
        $this->assertEquals($partner->description, 'algo');
        $this->assertEquals($partner->status, 'Operativo');
    }

    public function test_list_can_be_retrieved()
    {
        $this->withoutExceptionHandling();
        $vehicle = Vehicle::factory(2)->for(Partner::factory())->create();

        $response = $this->getJson('api/vehicles', [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);
        $response->assertOk()
            ->assertExactJson([
                'success' => true,
                'message' => 'index',
                'data'    => [
                    [
                        'id' => $vehicle[1]->id,
                        'plate' => $vehicle[1]->plate,
                        'num_control' => $vehicle[1]->num_control,
                        'description' => $vehicle[1]->description,
                        'status' => $vehicle[1]->status,
                        'partner_id' => $vehicle[1]->partner_id,
                        'partner' => [
                            'id' => $vehicle[1]->partner->id,
                            'first_name' => $vehicle[1]->partner->first_name,
                            'last_name' => $vehicle[1]->partner->last_name,
                            'ci' => $vehicle[1]->partner->ci,
                        ]
                        ],
                    [
                        'id' => $vehicle[0]->id,
                        'plate' => $vehicle[0]->plate,
                        'num_control' => $vehicle[0]->num_control,
                        'description' => $vehicle[0]->description,
                        'status' => $vehicle[0]->status,
                        'partner_id' => $vehicle[0]->partner_id,
                        'partner' => [
                            'id' => $vehicle[0]->partner->id,
                            'first_name' => $vehicle[0]->partner->first_name,
                            'last_name' => $vehicle[0]->partner->last_name,
                            'ci' => $vehicle[0]->partner->ci,
                        ]
                    ]
                ],
                'count'   => 1
            ]);

        $drivers = Vehicle::all();

       // $response->assertJsonPath('data', $drivers->toArray());
    }

    public function test_vehicle_can_be_show()
    {
        $this->withoutExceptionHandling();

        Vehicle::factory(1)->for(Partner::factory())->create();
        $vehicle = Vehicle::first();
        $response = $this->get('api/vehicles/' . $vehicle->id, [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);
        $response->assertOk()
            ->assertExactJson([
                'success' => true,
                'message' => 'Show con éxito',
                'data'    => [
                    'id' => $vehicle->id,
                    'plate' => $vehicle->plate,
                    'num_control' => $vehicle->num_control,
                    'description' => $vehicle->description,
                    'status' => $vehicle->status,
                    'partner_id' => $vehicle->partner_id,
                    'partner' => [
                        'id' => $vehicle->partner->id,
                        'first_name' => $vehicle->partner->first_name,
                        'last_name' => $vehicle->partner->last_name,
                        'ci' => $vehicle->partner->ci,
                    ]
                ],
                'count'   => 1
            ]);
        //dd($vehicle->toArray());

        //$response->assertJsonPath('data',$vehicle->toArray());
    }

    public function test_vehicle_can_be_updated()
    {
        $this->withoutExceptionHandling();
        $partner = Vehicle::factory(1)->create();
        $response = $this->putJson('api/vehicles/' . $partner[0]->id, [
            'plate' => 'algo Mas',
            'num_control' => 'algo Mas',
            'description' => 'algo Mas',
            'status' => 'Averiado',
        ], [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);
        $response->assertStatus(205)
            ->assertJsonFragment([
                'success' => true
            ]);

        $partner_bd = Vehicle::first();

        $this->assertEquals($partner_bd->plate, 'algo Mas');
        $this->assertEquals($partner_bd->num_control, 'algo Mas');
        $this->assertEquals($partner_bd->description, 'algo Mas');
        $this->assertEquals($partner_bd->status, 'Averiado');
    }

    public function test_vehicle_can_be_deleted()
    {
        $this->withoutExceptionHandling();
        $partner = Vehicle::factory(1)->create();
        $id = $partner[0]->id;
        $response = $this->deleteJson('api/vehicles/' . $id, [], [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);
        $response->assertStatus(202)
            ->assertJsonFragment([
                'success' => true
            ]);

        $partner_bd = Vehicle::find($id);

        $this->assertEquals($partner_bd, '');
    }

    public function test_fields_required_to_save_vehicle()
    {
        //$this->withoutExceptionHandling();
        $response = $this->postJson('api/vehicles', [], [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'success' => false
            ]);
    }

    public function test_unique_ci_in_vehicles()
    {
        Vehicle::create([
            'plate' => '123',
            'num_control' => 'algo Mas',
            'description' => 'algo Mas',
            'status' => 'Operativo',
        ]);

        $response = $this->postJson('api/vehicles', [
            'plate' => '123',
            'num_control' => 'algo Mas',
            'description' => 'algo Mas',
            'status' => 'Operativo',
        ], [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'success' => false
            ]);
    }

    public function test_fields_required_to_update_vehicle()
    {
        //$this->withoutExceptionHandling();
        Vehicle::create([
            'plate' => '123',
            'num_control' => 'algo Mas',
            'description' => 'algo Mas',
            'status' => 'Operativo',
        ]);
        $response = $this->putJson('api/vehicles/1', [], [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'success' => false
            ]);
    }

    public function test_unique_ci_in_update_vehicle()
    {
        Vehicle::create([
            'plate' => '123',
            'num_control' => '123',
            'description' => 'algo Mas',
            'status' => 'Operativo',
        ]);
        Vehicle::create([
            'plate' => '312',
            'num_control' => '321',
            'description' => 'algo Mas',
            'status' => 'Operativo',
        ]);

        $response = $this->putJson('api/vehicles/2', [
            'num_control'         => '123'
        ], [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);
        $response->assertStatus(422)
            ->assertJsonFragment([
                'success' => false
            ]);
    }

    public function test_unauthorized()
    {
        $response = $this->getJson('api/vehicles');
        $response->assertStatus(401);
    }
}
