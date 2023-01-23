<?php

namespace Tests\Unit;

use App\Models\Field;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

class FieldControllerTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /**
     * Test to verify we can get currency list
     * 
     * @return void
     */
    public function test_can_get_fields()
    {
        $response = $this->getJson('/api/fields');
 
        $response->assertStatus(200);
       
    }

    /**
     * Test to verify we can get field with valid
     * 
     * @return void
     */
    public function test_can_show_field_with_valid_id()
    {
        $mockField = Field::factory()->create(['id' => 1]);
        
        $response = $this->getJson('/api/fields'.'/'.$mockField->id);
 
        $response->assertStatus(200);
       
    }

    /**
     * Test to cannot show field with invalid id
     * 
     * @return void
     */
    public function test_cannot_show_field_with_invalid_id()
    {
        // use a transaction id that doesn't exist
        $mock_field_id = 10;

        $response = $this->getJson('/api/fields'.'/'.$mock_field_id .'/');
 
        $response->assertStatus(400);
       
    }

    /**
     * Test to ensure we can create(store) a new field with valid
     * 
     * @return void
     */
    public function test_can_create_field_with_valid_data()
    {
        $mockData = [
            'title' => 'Company',
            'type' => 'string',
            'required' => false
        ]; 

        $response = $this->postJson('/api/fields', $mockData);

        $response->assertStatus(200);

    }

     /**
     * Test to ensure we cannot create(store) a new field with invalid data
     * 
     * @return void
     */
    public function test_cannot_create_field_with_invalid_data()
    {
        $mockData = [
            'title' => 'Company',
            'type' => 'invalid_type',
            'required' => false
        ]; 

        $response = $this->postJson('/api/fields', $mockData);

        $response->assertStatus(422);

    }

    /**
     * Test to ensure we can update an field with valid data
     * 
     * @return void
     */
    public function test_can_update_field_with_valid_data()
    {        
        $mockField = Field::factory()->create(['id' => 1]);

        $mockData = [
            'title' => 'Company',
            'type' => 'string',
            'required' => false
        ]; 

        $response = $this->putJson('/api/fields' .'/'.$mockField->id, $mockData);

        $response->assertStatus(200);

    }

     /**
     * Test to ensure we cannot update an field with invalid data
     * 
     * @return void
     */
    public function test_cannot_update_field_with_invalid_data()
    {
        
        $mockField = Field::factory()->create(['id' => 1]); 

        $mockData = [
            'title' => 'Company',
            'type' => 'invalid_type',
        ]; 

        $response = $this->putJson('/api/fields' .'/'.$mockField->id , $mockData);

        $response->assertStatus(422);

    }

    /**
     * Test to ensure we can delete field with valid id
     * 
     * @return void
     */
    public function test_can_delete_field_with_valid_id()
    {
        $mockField = Field::factory()->create(['id' => 1]);

        $response = $this->deleteJson('/api/fields'. '/'.$mockField->id);

        $response->assertStatus(200);

    }

    /**
     * Assert that the given field is present in Json provided
     */
    private function assertJsonHasField(AssertableJson $json, Field $field): void
    {
        $json->where('id', $field->id)
            ->where('title', $field->title)
            ->where('type', $field->type)
            ->etc();
    }

    /**
     * Assert that the given field has been 
     * successfully inserted into the database
     */
    private function assertDatabaseHasRow(Field $field): void
    {
        $this->assertDatabaseHas(
            'fields',
            [
                'title' => $field->title,
                'type' => $field->type,
            ]
        );
    }
}