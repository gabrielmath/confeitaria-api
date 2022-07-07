<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\WaitingListRequest;
use App\Http\Resources\WaitingListResource;
use App\Models\Cake;
use App\Models\WaitingList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WaitingListController extends Controller
{
    public function __construct()
    {
        $this->middleware(['check.cake.waitingList'])->except(['index', 'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index(Cake $cake)
    {
        $waitingLists = $cake->waitingLists()->get();

        return WaitingListResource::collection($waitingLists, 'data');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param WaitingListRequest $request
     * @return WaitingListResource
     */
    public function store(WaitingListRequest $request, Cake $cake)
    {
        $cake->waitingLists()->create($request->validated());
        $newWaitingList = $cake->waitingLists()->latest()->first();

        return new WaitingListResource($newWaitingList);
    }

    /**
     * Display the specified resource.
     *
     * @param WaitingList $waitingList
     * @return WaitingListResource|JsonResponse
     */
    public function show(Cake $cake, WaitingList $waitingList)
    {
        if (!$cake->waitingLists()->where('id', $waitingList->id)->exists()) {
            return response()->json(['message' => 'The waiting list does not belong to the cake.'], 404);
        }

        return new WaitingListResource($waitingList);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param WaitingListRequest $request
     * @param WaitingList $waitingList
     * @return WaitingListResource|JsonResponse
     */
    public function update(WaitingListRequest $request, Cake $cake, WaitingList $waitingList)
    {
        if (!$cake->waitingLists()->where('id', $waitingList->id)->exists()) {
            return response()->json(['message' => 'The waiting list does not belong to the cake.'], 404);
        }

        $waitingList->update($request->validated());

        return new WaitingListResource($waitingList);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param WaitingList $waitingList
     * @return JsonResponse
     */
    public function destroy(Cake $cake, WaitingList $waitingList)
    {
        if (!$cake->waitingLists()->where('id', $waitingList->id)->exists()) {
            return response()->json(['message' => 'The waiting list does not belong to the cake.'], 404);
        }

        $waitingList->delete();

        return response()->json(['status' => 'OK', 'message' => 'Waiting List deleted'], 204);
    }
}
