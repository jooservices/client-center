<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCrawlingRequest;
use App\Http\Resources\RequestResource;
use App\Services\CrawlingService;
use Illuminate\Http\JsonResponse;

class CrawlingController extends Controller
{
    public function store(StoreCrawlingRequest $request, CrawlingService $service): JsonResponse
    {
        $request = $service->store($request->validated());

        return response()->json(RequestResource::make($request), 201);
    }
}
