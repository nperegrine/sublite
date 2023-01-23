<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Repository\SubscriberRepositoryInterface;
use App\Http\Requests\Subscribers\SaveRequest;
use Illuminate\Http\JsonResponse;

class SubscriberController extends Controller
{    
    protected $subscriberRepository;
    
    public function __construct(SubscriberRepositoryInterface $subscriberRepository)
    {
        $this->subscriberRepository = $subscriberRepository;
    }

    /**
     * Returns a list of all subscribers
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $subscribersFetch = $this->subscriberRepository->all();
        
        if ($subscribersFetch->hasError()) {
            return $this->errorResponse(
                $subscribersFetch->getErrorMessage()
            );
        }

        return $this->successListResponse($subscribersFetch->getItems()->all());
        
    }

    /**
     * Find the subscriber with id passed in params
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $subscriberFetch = $this->subscriberRepository->find($id);

        if ($subscriberFetch->hasError()) {
            return $this->errorResponse($subscriberFetch->getErrorMessage(), 400);
        }

        return $this->successResponse($subscriberFetch->getItems());
    }

    /**
     * Save a subscriber and return stored subscriber
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(SaveRequest $request): JsonResponse
    {
        $requestParams = $request->validated();
        $subscriber = new Subscriber($requestParams);
        $subscriberStore = $this->subscriberRepository->save($subscriber, $request->all());
        
        if ($subscriberStore->hasError()) {
            return $this->errorResponse(
                $subscriberStore->getErrorMessage()
            );
        }

        return $this->successResponse($subscriberStore->getItems());
    }

    /**
     * Updates a subscriber and returns the updated subscriber
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function update(SaveRequest $request, Subscriber $subscriber): JsonResponse
    {
        $requestParams = $request->validated();
        $subscriber->fill($requestParams);
        $subscriberStore = $this->subscriberRepository->save($subscriber, $request->all());
        
        if ($subscriberStore->hasError()) {
            return $this->errorResponse(
                $subscriberStore->getErrorMessage()
            );
        }

        return $this->successResponse($subscriberStore->getItems());
    }

    /**
    * Delete an subscriber
    *
    * @param int $id
    * @return JsonResponse
    */
    public function delete($id): JsonResponse
    {
        $subscriberDelete = $this->subscriberRepository->delete($id);
                
        if ($subscriberDelete->hasError()) {
            return $this->errorResponse(
                $subscriberDelete->getErrorMessage()
            );
        }

        return $this->successResponse($subscriberDelete->getItems());
    }
}