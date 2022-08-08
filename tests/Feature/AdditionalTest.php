<?php

namespace Tests\Feature;

use App\Models\Additional;
use App\Models\Coin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AdditionalTest extends TestCase
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

    public function test_creado_exitoso_adicionales(){
        $coin = Coin::create([
            'name' => 'Bolivar',
            'symbol' => 'Bs'
        ]);

        $response = $this->postJson('/api/additionals',[
            'description'   => 'Pago',
            'percent'       => '12.5',
            'coin_id'       => $coin->id,
            'type'          => 'Retencion',
        ], [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response
            ->assertCreated()
            ->assertExactJson([
                'success' => true,
                'message' => 'Creado con éxito',
                'data'    => [
                    'id' => 1,
                    'description'   => 'Pago',
                    'percent'       => '12.5',
                    'coin_id'       => $coin->id,
                    'type'          => 'Retencion',
                ],
                'count'   => 1
            ]);

        $additional = Additional::first();

        $this->assertEquals('Pago',$additional->description);
        $this->assertEquals('12.5',$additional->percent);
        $this->assertEquals(1,$additional->coin_id);
        $this->assertEquals('Retencion',$additional->type);
    }

    public function test_lista_de_adicionales(){
        $coin = Coin::create([
            'name' => 'Bolivar',
            'symbol' => 'bs'
        ]);
        Additional::create([
            'description'   => 'Pago',
            'percent'       => '12.5',
            'coin_id'       => $coin->id,
            'type'          => 'Retencion',
        ]);

        $response = $this->getJson('/api/additionals', [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response->assertOk()
                ->assertExactJson([
                    'success' => true,
                    'message' => 'index',
                    'data'    => [
                        [
                            'id' => 2,
                            'description'   => 'Pago',
                            'percent'       => '12.5',
                            'coin_id'       => $coin->id,
                            'quantity'      => null,
                            'type'          => 'Retencion',
                        ]
                    ],
                    'count'   => 1
                ]);
    }

    public function test_mostrar_adicional(){
        Additional::create([
            'description'   => 'Pago',
            'percent'       => '12.5',
            'coin_id'       => 1,
            'type'          => 'Retencion',
        ]);

        $response = $this->getJson('api/additionals/3', [
            'Authorization' => 'Bearer ' . self::createToken()
        ]);

        $response
        ->assertOk()
        ->assertExactJson([
            'success' => true,
            'message' => 'Show con éxito',
            'data'    => [
                    'id' => 3,
                    'description'   => 'Pago',
                    'percent'       => '12.5',
                    'coin_id'       => 1,
                    'quantity'      => null,
                    'type'          => 'Retencion',
            ],
            'count'   => 1
        ]);
    }

    public function test_editar_adicional(){
        $coin = Coin::create([
            'name' => 'Bolivar',
            'symbol' => 'bs'
        ]);
        $coin2 = Coin::create([
            'name' => 'Peso',
            'symbol' => 'cop'
        ]);
            Additional::create([
                'description'   => 'Pago',
                'percent'       => '12.5',
                'coin_id'       => $coin->id,
                'type'          => 'Retencion',
            ]);

            $response = $this->putJson('api/additionals/4',[
                'description'   => 'Editado',
                'percent'       => 1,
                'coin_id'       => $coin2->id,
                'type'          => 'Descuento',
            ], [
                'Authorization' => 'Bearer ' . self::createToken()
            ]);

            $response
            ->assertStatus(205)
            ->assertExactJson([
                'success' => true,
                'message' => 'Editado con éxito',
                'data'    => [
                        'id' => 4,
                        'description'   => 'Editado',
                        'percent'       => '1',
                        'quantity'      => null,
                        'coin_id'       => $coin2->id,
                        'type'          => 'Descuento',
                ],
                'count'   => 1
            ]);
    }

    public function test_eliminar_adicional(){
        //$this->withoutExceptionHandling();
            $additional = Additional::create([
                'description'   => 'Pago',
                'percent'       => '12.5',
                'coin_id'       => 1,
                'type'          => 'Retencion',
            ]);

            $response = $this->deleteJson('api/additionals/'. $additional->id,[], [
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
    }
}
