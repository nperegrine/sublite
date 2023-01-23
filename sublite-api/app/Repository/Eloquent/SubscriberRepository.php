<?php

namespace App\Repository\Eloquent;

use App\Models\Field;
use App\Models\Subscriber;
use App\Repository\Repository;
use App\Repository\SubscriberRepositoryInterface;
use App\Repository\Eloquent\Services\SubscriberService;
use App\Http\Resources\FieldResource;
use App\Http\Resources\SubscriberResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class SubscriberRepository extends Repository implements SubscriberRepositoryInterface {

     /**      
     * @var Model      
     */     
    protected $model;   
    
    /**
     * @var Service
     */
    private SubscriberService $subscriberService;

     /**
    * SubscriberRepository constructor.
    *
    * @param App\Models\Subscriber $model
    */
   public function __construct(Subscriber $model, SubscriberService $subscriberService)
   {
       $this->model = $model;
       $this->subscriberService = $subscriberService;
   }

    /**
     * @return 
     */
    public function all() {
        try {
            $subscribers = $this->model->with('fields')->get();
            $subscribersList = SubscriberResource::collection($subscribers);

        } catch (\Exception $exception) {
           
            Log::error(
                'Error fetching all subscribers',
                [
                    'message' => $exception->getMessage()
                ]
            );

            $error = true;
            $errorMessage = $exception->getMessage();
        }
        
        return (new Repository())
            ->setItems($subscribersList ?? [])
            ->setError($error ?? false)
            ->setErrorMessage($errorMessage ?? '');

    }

    /**
     * @param $id
     */
    public function find($id) {
       try {
          $subscriber = $this->model->with('fields')->findOrFail($id);
          $singleItem = new SubscriberResource($subscriber);

        } catch (\Exception $exception) {
           
            Log::error(
                'Error finding that subscriber',
                [
                    'message' => $exception->getMessage(),
                    'id' => $id
                ]
            );
            $error = true;
            
        }
        return (new Repository())
        ->setItems($singleItem ?? [])
        ->setError($error ?? false)
        ->setErrorMessage($errorMessage ?? '');

    }

    /**
     * @param $data
     */
    public function save($subscriber, $data) {

        try {
            $fieldsForSyncing = $this->subscriberService->sanitizeFieldsForSyncing(collect($data['fields'] ?? []));

            DB::transaction(
                static function () use ($subscriber, $fieldsForSyncing) {
                    $subscriber->save();
                    $subscriber->fields()->sync($fieldsForSyncing);
                }
            );

            $singleItem = new SubscriberResource($subscriber);

        } catch (\Exception $exception) {
            Log::error(
                'Error creating subscriber',
                [
                    'message' => $exception->getMessage(),
                    'data' => $data
                ]
            );
            $error = true;
        }

        return (new Repository())
        ->setItems($singleItem ?? [])
        ->setError($error ?? false)
        ->setErrorMessage($errorMessage ?? '');
    }

    /**
     * @param $id
     */
    public function delete($id) {

    try {
        $subscriber = $this->model->findOrFail($id);

        $subscriber->delete();

     } catch (\Exception $exception) {
            Log::error(
                'Error deleting subscriber from database',
                [
                    'message' => $exception->getMessage(),
                    'id' => $id
                ]
            );
            $error = true;
        }

        return (new Repository())
            ->setError($error ?? false)
            ->setErrorMessage($errorMessage ?? '');
    }
}