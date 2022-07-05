<?php

namespace Tests\Feature\Http\Controllers\Api\V1;

use App\Models\Cake;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CakeControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itShouldReturnACakesList()
    {
        $cakesList = Cake::factory(10)->create();

        $this
            ->getJson(route('api.v1.cakes.index'))
            ->assertSuccessful()
            ->assertJsonCount(10, 'data');
    }

    /** @test */
    public function itShouldCreateACake()
    {
        $name = $this->faker->name;
        $weight = $this->faker->numberBetween(500, 2000);
        $value = $this->faker->randomFloat(2, 50, 200);
        $availableQuantity = $this->faker->numberBetween(3, 15);
        $unitOfMeasure = 'g';

        $this
            ->postJson(route('api.v1.cakes.store'), [
                'name'               => $name,
                'weight'             => $weight,
                'value'              => $value,
                'available_quantity' => $availableQuantity
            ])
            ->assertJson([
                'cake' => [
                    'id'                 => 1,
                    'description'        => "Wonderful '{$name}' cake, worth \${$value} and weighing {$weight}{$unitOfMeasure}.",
                    'name'               => $name,
                    'weight'             => $weight,
                    'value'              => $value,
                    'available_quantity' => $availableQuantity,
                    'unit_of_measure'    => $unitOfMeasure
                ]
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function itShouldReturnAUnprocessableContentErrorWhenFormIsEmpty()
    {
        $name = '';
        $weight = '';
        $value = '';
        $availableQuantity = '';

        $this
            ->postJson(route('api.v1.cakes.store'), [
                'name'               => $name,
                'weight'             => $weight,
                'value'              => $value,
                'available_quantity' => $availableQuantity
            ])
            ->assertUnprocessable();
    }

    /** @test */
    public function itShouldShowAUniqueCake()
    {
        Cake::factory()->create();
        $cake = Cake::latest()->first();

        $this
            ->getJson(route('api.v1.cakes.show', ['cake' => $cake->id]))
            ->assertJson([
                'cake' => [
                    'id'                 => $cake->id,
                    'description'        => "Wonderful '{$cake->name}' cake, worth \${$cake->value} and weighing {$cake->weight}{$cake->unit_of_measure}.",
                    'name'               => $cake->name,
                    'weight'             => $cake->weight,
                    'value'              => $cake->value,
                    'available_quantity' => $cake->available_quantity,
                    'unit_of_measure'    => $cake->unit_of_measure
                ]
            ]);
    }

    /** @test */
    public function itShouldReturnNotFoundErrorWhenCakeNotExists()
    {
        Cake::factory(3)->create();

        $this
            ->getJson(route('api.v1.cakes.show', ['cake' => 4]))
            ->assertNotFound();
    }

    /** @test */
    public function itShouldUpdateACake()
    {
        Cake::factory(5)->create();

        $cake = Cake::first();

        $name = $this->faker->name;
        $weight = $this->faker->numberBetween(500, 2000);
        $value = $this->faker->randomFloat(2, 50, 200);
        $availableQuantity = $this->faker->numberBetween(3, 15);
        $unitOfMeasure = 'g';

        $this
            ->putJson(route('api.v1.cakes.update', ['cake' => $cake->id]), [
                'name'               => $name,
                'weight'             => $weight,
                'value'              => $value,
                'available_quantity' => $availableQuantity
            ])
            ->assertJson([
                'cake' => [
                    'id'                 => $cake->id,
                    'description'        => "Wonderful '{$name}' cake, worth \${$value} and weighing {$weight}{$unitOfMeasure}.",
                    'name'               => $name,
                    'weight'             => $weight,
                    'value'              => $value,
                    'available_quantity' => $availableQuantity,
                    'unit_of_measure'    => $unitOfMeasure
                ]
            ])
            ->assertSuccessful();
    }

    /** @test */
    public function itShouldReturnUnprocessableContentErrorWhenUpdateCakeWithFormEmpty()
    {
        Cake::factory(5)->create();

        $cake = Cake::first();

        $name = '';
        $weight = '';
        $value = '';
        $availableQuantity = '';

        $this
            ->putJson(route('api.v1.cakes.update', ['cake' => $cake->id]), [
                'name'               => $name,
                'weight'             => $weight,
                'value'              => $value,
                'available_quantity' => $availableQuantity
            ])
            ->assertUnprocessable();
    }

    /** @test */
    public function itShouldDeleteACake()
    {
        Cake::factory(5)->create();
        $cake = Cake::first();

        $this->deleteJson(route('api.v1.cakes.destroy', ['cake' => $cake->id]))
            ->assertNoContent();
    }

    /** @test */
    public function itShouldReturnNotFoundErrorWhenDeleteACakeNotExists()
    {
        Cake::factory(5)->create();

        $this->deleteJson(route('api.v1.cakes.destroy', ['cake' => 6]))
            ->assertNotFound();
    }
}
