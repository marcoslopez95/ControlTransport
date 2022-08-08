<?php

namespace Tests\Feature;

use App\Models\Partner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SocioTest extends TestCase
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
    public function test_socio_can_be_created(){
        $this->withoutExceptionHandling();

        $response = $this->postJson('/api/partners',[
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

        $partner = Partner::first();

        $this->assertCount(1,Partner::all());
        $this->assertEquals($partner->first_name,'Marcos');
    }

    public function test_list_can_be_retrieved(){
        $this->withoutExceptionHandling();
        Partner::factory(5)->create();

        $response = $this->getJson('api/partners',[
            'Authorization' => 'Bearer '.self::createToken()
        ]);
        $response->assertOk()
            ->assertJsonFragment([
            'success' => true
        ]);

        $partners = Partner::all();

        $response->assertJsonPath('data',$partners->toArray());
    }

    public function test_partner_can_be_show(){
        $this->withoutExceptionHandling();

        Partner::factory(1)->create();
        $partner = Partner::first();
        $response = $this->get('api/partners/'.$partner->id,[
            'Authorization' => 'Bearer '.self::createToken()
        ]);
        $response->assertOk()
            ->assertJsonFragment([
            'success' => true
        ]
        );

        $response->assertJsonPath('data',$partner->toArray());
    }

    public function test_partner_can_be_updated(){
        $this->withoutExceptionHandling();
        $partner = Partner::factory(1)->create();
        $response = $this->putJson('api/partners/'.$partner[0]->id,[
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

        $partner_bd = Partner::first();

        $this->assertEquals($partner_bd->first_name, 'Marcos');
        $this->assertEquals($partner_bd->last_name, 'Lopez');
        $this->assertEquals($partner_bd->ci, '123');
    }

    public function test_partner_can_be_deleted(){
        $this->withoutExceptionHandling();
        $partner = Partner::factory(1)->create();
        $id = $partner[0]->id;
        $response = $this->deleteJson('api/partners/'.$id,[],[
            'Authorization' => 'Bearer '.self::createToken()
        ]);
        $response->assertStatus(202)
                ->assertJsonFragment([
                    'success' => true
                ]);

        $partner_bd = Partner::find($id);

        $this->assertEquals($partner_bd, '');
    }

    public function test_fields_required_to_save_partner(){
        //$this->withoutExceptionHandling();
        $response = $this->postJson('api/partners',[],[
            'Authorization' => 'Bearer '.self::createToken()
        ]);

        $response->assertStatus(422)
                ->assertJsonFragment([
                    'success' => false
                ]);
    }

    public function test_unique_ci_in_partners(){
        Partner::create([
            'first_name' => 'Nombre',
            'last_name'  => 'Apellido',
            'ci'         => '123'
        ]);

        $response = $this->postJson('api/partners',[
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

    public function test_fields_required_to_update_partner(){
        //$this->withoutExceptionHandling();
        Partner::create([
            'first_name' => 'Nombre',
            'last_name'  => 'Apellido',
            'ci'         => '123'
        ]);
        $response = $this->putJson('api/partners/1',[],[
            'Authorization' => 'Bearer '.self::createToken()
        ]);

        $response->assertStatus(422)
                ->assertJsonFragment([
                    'success' => false
                ]);
    }

    public function test_unauthorized(){
        $respose = $this->getJson('api/partners');
        $respose->assertStatus(401);
    }

    public function test_unique_ci_in_update_partner(){
        Partner::create([
            'first_name' => 'Nombre',
            'last_name'  => 'Apellido',
            'ci'         => '123'
        ]);
        Partner::create([
            'first_name' => 'Nombre',
            'last_name'  => 'Apellido',
            'ci'         => '312'
        ]);

        $response = $this->putJson('api/partners/2',[
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
