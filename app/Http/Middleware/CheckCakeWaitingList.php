<?php

namespace App\Http\Middleware;

use App\Models\Cake;
use App\Models\WaitingList;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CheckCakeWaitingList
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return JsonResponse|RedirectResponse|Response
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var Cake $cake */
        $cake = $request->route('cake');

        /** @var WaitingList $waitingList */
        $waitingList = $request->route('waitingList');

        if (!$cake->waitingLists()->where('id', $waitingList->id)->exists()) {
            return response()->json(['message' => 'The waiting list does not belong to the cake.'], 404);
        }

        return $next($request);
    }
}
