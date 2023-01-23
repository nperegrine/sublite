<?php

namespace App\Http\Controllers;

use App\Repository\FieldRepositoryInterface;

use App\Http\Requests\Fields\SaveRequest;
use Illuminate\Http\JsonResponse;

class FieldController extends Controller
{    
    protected $fieldRepository;
    
    public function __construct(FieldRepositoryInterface $fieldRepository)
    {
        $this->fieldRepository = $fieldRepository;
    }

    /**
     * Returns a list of all fields
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $fieldsFetch = $this->fieldRepository->all();
        
        if ($fieldsFetch->hasError()) {
            return $this->errorResponse(
                $fieldsFetch->getErrorMessage()
            );
        }

        return $this->successListResponse($fieldsFetch->getItems()->all());
        
    }

    /**
     * Find the field with id passed in params
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $fieldFetch = $this->fieldRepository->find($id);

        if ($fieldFetch->hasError()) {
            return $this->errorResponse($fieldFetch->getErrorMessage(), 400);
        }

        return $this->successResponse($fieldFetch->getItems());
    }

    /**
     * Store a new field and return stored field
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(SaveRequest $request): JsonResponse
    {
        $fieldStore = $this->fieldRepository->store($request->all());
        
        if ($fieldStore->hasError()) {
            return $this->errorResponse(
                $fieldStore->getErrorMessage()
            );
        }

        return $this->successResponse($fieldStore->getItems());
    }

    /**
     * Update an field and return updated field
     *
     * @param int $id
     * @param UpdateRequest $request
     * @return JsonResponse
     */
    public function update(SaveRequest $request, $id): JsonResponse
    {
        $fieldUpdate = $this->fieldRepository->update($request, $id);
        
        if ($fieldUpdate->hasError()) {
            return $this->errorResponse(
                $fieldUpdate->getErrorMessage()
            );
        }

        return $this->successResponse($fieldUpdate->getItems());
    }

    /**
    * Delete an field
    *
    * @param int $id
    * @return JsonResponse
    */
    public function delete($id): JsonResponse
    {
        $fieldDelete = $this->fieldRepository->delete($id);
                
        if ($fieldDelete->hasError()) {
            return $this->errorResponse(
                $fieldDelete->getErrorMessage()
            );
        }

        return $this->successResponse($fieldDelete->getItems());
    }
}