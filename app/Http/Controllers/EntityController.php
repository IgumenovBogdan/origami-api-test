<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\CreateEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use App\Services\EntityService;
use Illuminate\Http\JsonResponse;

class EntityController extends Controller
{
    public function __construct(private readonly EntityService $entityService)
    {}

    public function index()
    {
        //
    }

    public function store(CreateEntityRequest $request): JsonResponse
    {
        return $this->entityService->createEntity($request);
    }

    public function show(string $id)
    {
        //
    }

    public function update(UpdateEntityRequest $request, string $id): JsonResponse
    {
        return $this->entityService->updateEntity($request, $id);
    }

    public function destroy(string $id)
    {
        //
    }
}
