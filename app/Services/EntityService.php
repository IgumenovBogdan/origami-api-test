<?php

declare(strict_types=1);

namespace App\Services;

use App\Http\Requests\CreateEntityRequest;
use App\Http\Requests\UpdateEntityRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;

class EntityService
{
    public function __construct(
        private readonly string $origamiUsername,
        private readonly string $origamiPassword,
        private readonly string $origamiApiUrl
    )
    {}

    public function createEntity(CreateEntityRequest $request): JsonResponse
    {
        $response = Http::post($this->origamiApiUrl . '/create_instance/format/json', [
            'username' => $this->origamiUsername,
            'password' => $this->origamiPassword,
            'entity_data_name' => $request->entity_data_name,
            'form_data' => $request->entityFormData
        ]);

        return response()->json($response->json());
    }

    public function updateEntity(UpdateEntityRequest $request, string $id): JsonResponse
    {
        $response = Http::post($this->origamiApiUrl . '/update_instance_fields/format/json', [
            'username' => $this->origamiUsername,
            'password' => $this->origamiPassword,
            'entity_data_name' => $request->entity_data_name,
            'filter' => [
                [
                    "_id",
                    "=",
                    $id
                ]
            ],
            'field' => $request->fields
        ]);

        return response()->json($response->json());
    }
}
