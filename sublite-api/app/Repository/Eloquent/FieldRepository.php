<?php

namespace App\Repository\Eloquent;

use App\Models\Field;
use App\Repository\FieldRepositoryInterface;
use App\Repository\Repository;
use App\Http\Resources\FieldResource;

use Illuminate\Support\Facades\Log; 

class FieldRepository extends Repository implements FieldRepositoryInterface {

     /**      
     * @var Model      
     */     
    protected $model;     

     /**
    * FieldRepository constructor.
    *
    * @param App\Models\Field $model
    */
   public function __construct(Field $model)
   {
       $this->model = $model;
   }


    /**
     * @return 
     */
    public function all() {
        try {
            $fields = $this->model->all();
            $fieldsList = FieldResource::collection($fields);

        } catch (\Exception $exception) {
           
            Log::error(
                'Error fetching all fields',
                [
                    'message' => $exception->getMessage()
                ]
            );

            $error = true;
            $errorMessage = $exception->getMessage();
        }
        
        return (new Repository())
            ->setItems($fieldsList ?? [])
            ->setError($error ?? false)
            ->setErrorMessage($errorMessage ?? '');

    }

    /**
     * @param $id
     */
    public function find($id) {
       try {
          $field = $this->model->findOrFail($id);
          $singleItem = new FieldResource($field);

        } catch (\Exception $exception) {
           
            Log::error(
                'Error finding that field',
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
    public function store($data) {

        try {
            $field = $this->model->create($data);

            $singleItem = new FieldResource($field);

        } catch (\Exception $exception) {
            Log::error(
                'Error creating field',
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
     * @param $data
     */
    public function update($data, $id) {

       try {
        $field = $this->model->findOrFail($id);
        $field->update($data->all());
        $singleItem = new FieldResource($field);

       } catch (\Exception $exception) {
            Log::error(
                'Error updating field',
                [
                    'message' => $exception->getMessage(),
                    'data' => $data,
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
     * @param $id
     */
    public function delete($id) {

    try {
        $field = $this->model->findOrFail($id);

        $field->delete();

     } catch (\Exception $exception) {
            Log::error(
                'Error deleting field from database',
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