<?php

namespace Tests\Feature;

use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    public function test_vehicle_can_be_created(){
        $this->withoutExceptionHandling();

        $response = $this->postJson('/api/vehicles',[
            'plate' => 'algo',
            'num_control' => 'algo',
            'description' => 'algo',
            'status' => 'Operativo',
        ]);

        $response->assertStatus(201)
                ->assertJsonFragment([
                    'success' => true
                ]);

        $partner = Vehicle::first();

        $this->assertCount(1,Vehicle::all());
        $this->assertEquals($partner->plate,'algo');
        $this->assertEquals($partner->num_control, 'algo');
        $this->assertEquals($partner->description, 'algo');
        $this->assertEquals($partner->status, 'Operativo');
    }

    public function test_list_can_be_retrieved(){
        $this->withoutExceptionHandling();
        Vehicle::factory(5)->create();

        $response = $this->getJson('api/vehicles');
        $response->assertOk()
            ->assertJsonFragment([
            'success' => true
        ]);

        $drivers = Vehicle::all();

        $response->assertJsonPath('data',$drivers->toArray());
    }

    public function test_vehicle_can_be_show(){
        $this->withoutExceptionHandling();

        Vehicle::factory(1)->create();
        $partner = Vehicle::first();
        $response = $this->get('api/vehicles/'.$partner->id);
        $response->assertOk()
            ->assertJsonFragment([
            'success' => true
        ]);

        $response->assertJsonPath('data',$partner->toArray());
    }

    public function test_vehicle_can_be_updated(){
        $this->withoutExceptionHandling();
        $partner = Vehicle::factory(1)->create();
        $response = $this->putJson('api/vehicles/'.$partner[0]->id,[
            'plate' => 'algo Mas',
            'num_control' => 'algo Mas',
            'description' => 'algo Mas',
            'status' => 'Averiado',
        ]);
        $response->assertStatus(205)
                ->assertJsonFragment([
                    'success' => true
                ]);

        $partner_bd = Vehicle::first();

        $this->assertEquals($partner_bd->plate,'algo Mas');
        $this->assertEquals($partner_bd->num_control,'algo Mas');
        $this->assertEquals($partner_bd->description,'algo Mas');
        $this->assertEquals($partner_bd->status,'Averiado');
    }

    public function test_vehicle_can_be_deleted(){
        $this->withoutExceptionHandling();
        $partner = Vehicle::factory(1)->create();
        $id = $partner[0]->id;
        $response = $this->deleteJson('api/vehicles/'.$id);
        $response->assertStatus(202)
                ->assertJsonFragment([
                    'success' => true
                ]);

        $partner_bd = Vehicle::find($id);

        $this->assertEquals($partner_bd, '');
    }

    public function test_fields_required_to_save_vehicle(){
        //$this->withoutExceptionHandling();
        $response = $this->postJson('api/vehicles',[]);

        $response->assertStatus(422)
                ->assertJsonFragment([
                    'success' => false
                ]);
    }

    public function test_unique_ci_in_vehicles(){
        Vehicle::create([
            'plate' => '123',
            'num_control' => 'algo Mas',
            'description' => 'algo Mas',
            'status' => 'Operativo',
        ]);

        $response = $this->postJson('api/vehicles',[
            'plate' => '123',
            'num_control' => 'algo Mas',
            'description' => 'algo Mas',
            'status' => 'Operativo',
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'success' => false
            ]);
    }

    public function test_fields_required_to_update_vehicle(){
        //$this->withoutExceptionHandling();
        Vehicle::create([
            'plate' => '123',
            'num_control' => 'algo Mas',
            'description' => 'algo Mas',
            'status' => 'Operativo',
        ]);
        $response = $this->putJson('api/vehicles/1',[]);

        $response->assertStatus(422)
                ->assertJsonFragment([
                    'success' => false
                ]);
    }

    public function test_unique_ci_in_update_vehicle(){
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

        $response = $this->putJson('api/vehicles/2',[
            'num_control'         => '123'
        ]);
        $response->assertStatus(422)
            ->assertJsonFragment([
                'success' => false
            ]);
    }
}
