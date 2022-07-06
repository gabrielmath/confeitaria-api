<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Client;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itShouldReturnAClientsList()
    {
        $clientsList = Client::factory(10)->create();

        $this
            ->getJson(route('api.v1.clients.index'))
            ->assertSuccessful()
            ->assertJsonCount(10, 'data');
    }

    /** @test */
    public function itShouldCreateAClient()
    {
        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail();

        $this
            ->postJson(route('api.v1.clients.store'), [
                'name'  => $name,
                'email' => $email,
            ])
            ->assertJson([
                'client' => [
                    'id'    => 1,
                    'name'  => $name,
                    'email' => $email,
                ]
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function itShouldReturnAUnprocessableContentErrorWhenFormIsEmpty()
    {
        $name = '';
        $email = '';

        $this
            ->postJson(route('api.v1.clients.store'), [
                'name'  => $name,
                'email' => $email,
            ])
            ->assertUnprocessable();
    }

    /** @test */
    public function itShouldReturnAUnprocessableContentErrorWhenDuplicateEmail()
    {
        $client = Client::factory()->create();

        $name = $this->faker->name;
        $email = $client->email;

        $this
            ->postJson(route('api.v1.clients.store'), [
                'name'  => $name,
                'email' => $email,
            ])
            ->assertUnprocessable();
    }

    /** @test */
    public function itShouldShowAUniqueClient()
    {
        Client::factory()->create();
        $client = Client::latest()->first();

        $this
            ->getJson(route('api.v1.clients.show', ['client' => $client->id]))
            ->assertJson([
                'client' => [
                    'id'    => 1,
                    'name'  => $client->name,
                    'email' => $client->email,
                ]
            ]);
    }

    /** @test */
    public function itShouldReturnNotFoundErrorWhenClientNotExists()
    {
        Client::factory(3)->create();

        $this
            ->getJson(route('api.v1.clients.show', ['client' => 4]))
            ->assertNotFound();
    }

    /** @test */
    public function itShouldUpdateAClient()
    {
        Client::factory(5)->create();

        $client = Client::first();

        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail();

        $this
            ->putJson(route('api.v1.clients.update', ['client' => $client->id]), [
                'name'  => $name,
                'email' => $email,
            ])
            ->assertJson([
                'client' => [
                    'id'    => $client->id,
                    'name'  => $name,
                    'email' => $email,

                ]
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function itShouldUpdateAClientWithTheSameEmail()
    {
        Client::factory(5)->create();

        $client = Client::first();

        $name = $this->faker->name;

        $this
            ->putJson(route('api.v1.clients.update', ['client' => $client->id]), [
                'name'  => $name,
                'email' => $client->email,
            ])
            ->assertJson([
                'client' => [
                    'id'    => $client->id,
                    'name'  => $name,
                    'email' => $client->email,

                ]
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function itShouldReturnUnprocessableContentErrorWhenUpdateClientWithFormEmpty()
    {
        Client::factory(5)->create();

        $client = Client::first();

        $name = '';
        $email = '';

        $this
            ->putJson(route('api.v1.clients.update', ['client' => $client->id]), [
                'name'  => $name,
                'email' => $email,
            ])
            ->assertUnprocessable();
    }

    /** @test */
    public function itShouldDeleteAClient()
    {
        Client::factory(5)->create();
        $client = Client::first();

        $this->deleteJson(route('api.v1.clients.destroy', ['client' => $client->id]))
            ->assertNoContent();
    }

    /** @test */
    public function itShouldReturnNotFoundErrorWhenDeleteAClientNotExists()
    {
        Client::factory(5)->create();

        $this->deleteJson(route('api.v1.clients.destroy', ['client' => 6]))
            ->assertNotFound();
    }
}
