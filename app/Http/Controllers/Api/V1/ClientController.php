<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $clients = Client::query()->get();

        return ClientResource::collection($clients, 'data');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ClientRequest $request
     * @return ClientResource
     */
    public function store(ClientRequest $request)
    {
        Client::create($request->validated());
        $newClient = Client::latest()->first();

        return new ClientResource($newClient);
    }

    /**
     * Display the specified resource.
     *
     * @param Client $client
     * @return ClientResource
     */
    public function show(Client $client)
    {
        return new ClientResource($client);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ClientRequest $request
     * @param Client $client
     * @return ClientResource
     */
    public function update(ClientRequest $request, Client $client)
    {
        $client->update($request->validated());

        return new ClientResource($client);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Client $client
     * @return JsonResponse
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return response()->json(['status' => 'OK', 'message' => 'Client deleted'], 204);
    }
}
