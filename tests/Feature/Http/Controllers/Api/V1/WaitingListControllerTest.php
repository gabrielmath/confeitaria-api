<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Cake;
use App\Models\WaitingList;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WaitingListControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itShouldReturnAWaitingList()
    {
        Cake::factory()->create();
        $cake = Cake::first();

        WaitingList::factory(10)->create(['cake_id' => $cake->id]);

        $this
            ->getJson(route('api.v1.cakes.waitingLists.index', ['cake' => $cake->id]))
            ->assertSuccessful()
            ->assertJsonCount(10, 'data');
    }

    /** @test */
    public function itShouldCreateAWaitingList()
    {
        Cake::factory()->create();
        $cake = Cake::first();

        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail();

        $this
            ->postJson(route('api.v1.cakes.waitingLists.store', ['cake' => $cake->id]), [
                'name'  => $name,
                'email' => $email,
            ])
            ->assertJson([
                'waitingList' => [
                    'id'      => 1,
                    'cake_id' => $cake->id,
                    'name'    => $name,
                    'email'   => $email,
                ]
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function itShouldReturnAUnprocessableContentErrorWhenFormIsEmpty()
    {
        Cake::factory()->create();
        $cake = Cake::first();

        $name = '';
        $email = '';

        $this
            ->postJson(route('api.v1.cakes.waitingLists.store', ['cake' => $cake->id]), [
                'name'  => $name,
                'email' => $email,
            ])
            ->assertUnprocessable();
    }

    /** @test */
    public function itShouldReturnANotFoundErrorWhenCakeNotExists()
    {
        Cake::factory()->create();
        $cake = Cake::first();

        $name = '';
        $email = '';

        $this
            ->postJson(route('api.v1.cakes.waitingLists.store', ['cake' => 2]), [
                'name'  => $name,
                'email' => $email,
            ])
            ->assertNotFound();
    }

    /** @test */
    public function itShouldShowAUniqueWaitingList()
    {
        Cake::factory()->create();
        $cake = Cake::first();

        WaitingList::factory()->create(['cake_id' => $cake->id]);
        $waitingList = WaitingList::latest()->first();

        $this
            ->getJson(
                route('api.v1.cakes.waitingLists.show', ['cake' => $cake->id, 'waitingList' => $waitingList->id])
            )
            ->assertJson([
                'waitingList' => [
                    'id'      => 1,
                    'cake_id' => $cake->id,
                    'name'    => $waitingList->name,
                    'email'   => $waitingList->email,
                ]
            ]);
    }

    /** @test */
    public function itShouldReturnNotFoundErrorWhenWaitingListNotExists()
    {
        Cake::factory()->create();
        $cake = Cake::first();

        WaitingList::factory(3)->create(['cake_id' => $cake->id]);

        $this
            ->getJson(route('api.v1.cakes.waitingLists.show', ['cake' => $cake->id, 'waitingList' => 4]))
            ->assertNotFound();
    }

    /** @test */
    public function itShouldReturnNotFoundErrorWhenWaitingListIsForADifferentCake()
    {
        Cake::factory(2)->create();
        $cake = Cake::first();
        $otherCake = Cake::orderByDesc('id')->first();

        WaitingList::factory(2)->create(['cake_id' => $cake->id]);
        WaitingList::factory(2)->create(['cake_id' => $otherCake->id]);

        $differentWaitingList = WaitingList::orderByDesc('id')->first();

        $this
            ->getJson(
                route(
                    'api.v1.cakes.waitingLists.show',
                    ['cake' => $cake->id, 'waitingList' => $differentWaitingList->id]
                )
            )
            ->assertNotFound();
    }

    /** @test */
    public function itShouldUpdateAWaitingList()
    {
        Cake::factory()->create();
        $cake = Cake::first();

        WaitingList::factory(5)->create(['cake_id' => $cake->id]);

        $waitingList = WaitingList::first();

        $name = $this->faker->name;
        $email = $this->faker->unique()->safeEmail();

        $this
            ->putJson(
                route('api.v1.cakes.waitingLists.update', ['cake' => $cake->id, 'waitingList' => $waitingList->id]),
                [
                    'name'  => $name,
                    'email' => $email,
                ]
            )
            ->assertJson([
                'waitingList' => [
                    'id'      => $waitingList->id,
                    'cake_id' => $waitingList->cake->id,
                    'name'    => $name,
                    'email'   => $email,

                ]
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function itShouldReturnUnprocessableContentErrorWhenUpdateWaitingListWithFormEmpty()
    {
        Cake::factory()->create();
        $cake = Cake::first();

        WaitingList::factory(5)->create(['cake_id' => $cake->id]);

        $waitingList = WaitingList::first();

        $name = '';
        $email = '';

        $this
            ->putJson(
                route('api.v1.cakes.waitingLists.update', ['cake' => $cake->id, 'waitingList' => $waitingList->id]),
                [
                    'name'  => $name,
                    'email' => $email,
                ]
            )
            ->assertUnprocessable();
    }

    /** @test */
    public function itShouldReturnNotFoundErrorWhenUpdateWaitingListIsForADifferentCake()
    {
        Cake::factory(2)->create();
        $cake = Cake::first();
        $otherCake = Cake::orderByDesc('id')->first();

        WaitingList::factory(2)->create(['cake_id' => $cake->id]);
        WaitingList::factory(2)->create(['cake_id' => $otherCake->id]);

        $differentWaitingList = WaitingList::orderByDesc('id')->first();

        $name = 'Name';
        $email = 'name@email.com';

        $this
            ->putJson(
                route(
                    'api.v1.cakes.waitingLists.update',
                    ['cake' => $cake->id, 'waitingList' => $differentWaitingList->id]
                ),
                [
                    'name'  => $name,
                    'email' => $email,
                ]
            )
            ->assertNotFound();
    }

    /** @test */
    public function itShouldDeleteAWaitingList()
    {
        Cake::factory()->create();
        $cake = Cake::first();

        WaitingList::factory(5)->create(['cake_id' => $cake->id]);
        $waitingList = WaitingList::first();

        $this->deleteJson(
            route('api.v1.cakes.waitingLists.destroy', ['cake' => $cake->id, 'waitingList' => $waitingList->id])
        )
            ->assertNoContent();
    }

    /** @test */
    public function itShouldReturnNotFoundErrorWhenDeleteAWaitingListNotExists()
    {
        Cake::factory()->create();
        $cake = Cake::first();

        WaitingList::factory(5)->create();

        $this->deleteJson(route('api.v1.cakes.waitingLists.destroy', ['cake' => $cake->id, 'waitingList' => 6]))
            ->assertNotFound();
    }

    /** @test */
    public function itShouldReturnNotFoundErrorWhenDeleteAWaitingListIsForADifferentCake()
    {
        Cake::factory(2)->create();
        $cake = Cake::first();
        $otherCake = Cake::orderByDesc('id')->first();

        WaitingList::factory(2)->create(['cake_id' => $cake->id]);
        WaitingList::factory(2)->create(['cake_id' => $otherCake->id]);

        $differentWaitingList = WaitingList::orderByDesc('id')->first();

        $this->deleteJson(
            route('api.v1.cakes.waitingLists.destroy', ['cake' => $cake->id, 'waitingList' => $differentWaitingList->id]
            )
        )
            ->assertNotFound();
    }
}
