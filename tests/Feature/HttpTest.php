<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HttpTest extends TestCase
{
    public function test_listar_ficheros()
    {
        $response = $this->withHeaders([
            'token' => '12345',
        ])->get('/api/ficheros');

        $response->assertStatus(200);
    }

    public function test_guardar_ficheros_sin_subir_ficheros()
    {
        $response = $this->withHeaders([
            'token' => '12345',
        ])->post('/api/ficheros', ['archivo']);

        $response->assertStatus(400);
    }

    public function test_guardar_ficheros_data_otro_nombre()
    {
        $response = $this->withHeaders([
            'token' => '12345',
        ])->post('/api/ficheros', ['fichero' => 2]);

        $response->assertStatus(400);
    }

    public function test_guardar_ficheros()
    {
        $response = $this->withHeaders([
            'token' => '12345',
        ])->post('/api/ficheros', ['archivo[]' => 2]);

        $response->assertStatus(201);
    }
}
