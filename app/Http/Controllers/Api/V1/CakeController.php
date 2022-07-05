<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\CakeRequest;
use App\Http\Resources\CakeResource;
use App\Models\Cake;
use Illuminate\Http\JsonResponse;

class CakeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $cakes = Cake::query()->get();

        return CakeResource::collection($cakes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CakeRequest $request
     * @return CakeResource
     */
    public function store(CakeRequest $request)
    {
        Cake::create($request->validated());
        $newCake = Cake::latest()->first();

        return new CakeResource($newCake);
    }

    /**
     * Display the specified resource.
     *
     * @param Cake $cake
     * @return CakeResource
     */
    public function show(Cake $cake)
    {
        return new CakeResource($cake);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CakeRequest $request
     * @param Cake $cake
     * @return CakeResource
     */
    public function update(CakeRequest $request, Cake $cake)
    {
        $cake->update($request->validated());

        return new CakeResource($cake);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Cake $cake
     * @return JsonResponse
     */
    public function destroy(Cake $cake)
    {
        $cake->delete();

        return response()->json(['status' => 'OK', 'message' => 'Cake deleted'], 204);
    }
}
