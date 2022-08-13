<?php

namespace Tests\Feature;

use App\Models\Coin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CoinTest extends TestCase
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

    public function test_created_successfull_coin()
    {
       // $this->withoutExceptionHandling();

        $response = $this->postJson('/api/coins', [
            'name'   => 'Bolivar Digital',
            'symbol' => 'BsD'
        ], [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response
            ->assertCreated()
            ->assertExactJson([
                'success' => true,
                'message' => 'Creado con éxito',
                'data'    => [
                    'id' => 5,
                    'name'   => 'Bolivar Digital',
                    'symbol' => 'BsD'
                ],
                'count'   => 1
            ]);

        $coin = Coin::all();
        $this->assertCount(1, $coin->toArray());
        $this->assertEquals($coin[0]->name, 'Bolivar Digital');
        $this->assertEquals($coin[0]->symbol, 'BsD');
    }

    public function test_show_all_coins()
    {
        $this->withoutExceptionHandling();
        Coin::create([
            'name' => 'Bolivar',
            'symbol' => 'Bs'
        ]);

        $response = $this->getJson('/api/coins', [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response
            ->assertOk()
            ->assertExactJson([
                'success' => true,
                'message' => 'index',
                'data'    => [
                    [
                        'id' => 6,
                        'name'   => 'Bolivar',
                        'symbol' => 'Bs'
                    ]
                ],
                'count'   => 1
            ]);

        $coin = Coin::all();
        $this->assertCount(1, $coin->toArray());
        $this->assertEquals($coin[0]->name, 'Bolivar');
        $this->assertEquals($coin[0]->symbol, 'Bs');
    }

    public function test_show_a_coin(){
        $this->withoutExceptionHandling();
        $coin = Coin::create([
            'name' => 'Bolivar',
            'symbol' => 'Bs'
        ]);

        $response = $this->getJson('/api/coins/'.$coin->id, [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response
            ->assertOk()
            ->assertExactJson([
                'success' => true,
                'message' => 'Show con éxito',
                'data'    => [
                        'id' => $coin->id,
                        'name'   => 'Bolivar',
                        'symbol' => 'Bs'
                ],
                'count'   => 1
            ]);

        $coin = Coin::all();
        $this->assertCount(1, $coin->toArray());
        $this->assertEquals($coin[0]->name, 'Bolivar');
        $this->assertEquals($coin[0]->symbol, 'Bs');
    }

    public function test_edit_a_coin(){
        $this->withoutExceptionHandling();
        $coin = Coin::create([
            'name' => 'Bolivar',
            'symbol' => 'Bs'
        ]);

        $response = $this->putJson('/api/coins/'.$coin->id,[
            'name' => 'Peso Colombiano',
            'symbol' => 'cop'
        ], [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response
            ->assertStatus(205)
            ->assertExactJson([
                'success' => true,
                'message' => 'Editado con éxito',
                'data'    => [
                        'id' => $coin->id,
                        'name'   => 'Peso Colombiano',
                        'symbol' => 'cop'
                ],
                'count'   => 1
            ]);

        $coin = Coin::all();
        $this->assertCount(1, $coin->toArray());
        $this->assertEquals($coin[0]->name, 'Peso Colombiano');
        $this->assertEquals($coin[0]->symbol, 'cop');
    }

    public function test_delete_a_coin(){
        $this->withoutExceptionHandling();
        $coin = Coin::create([
            'name' => 'Bolivar',
            'symbol' => 'Bs'
        ]);

        $response = $this->deleteJson('/api/coins/'.$coin->id,[], [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response
            ->assertStatus(202)
            ->assertExactJson([
                'success' => true,
                'message' => 'Eliminado con éxito',
                'data'    => true,
                'count'   => 1
            ]);

        $coin = Coin::all();
        $this->assertCount(0, $coin->toArray());
    }

    public function test_validate_field_when_create_a_coin(){
       // $this->withoutExceptionHandling();

        $response = $this->postJson('/api/coins',[
            'name' => 'Bolivares',
        ], [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response
            ->assertStatus(422)
            ->assertExactJson([
                'success' => false,
                'message' => 'Error de validación',
                'data'    => 'El campo symbol es requerido',
                'count'   => 0
            ]);

        $coin = Coin::all();
        $this->assertCount(0, $coin->toArray());
    }
}
