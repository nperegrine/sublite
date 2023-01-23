<?php

namespace Tests\Unit;

use App\Models\Subscriber;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SubscriberControllerTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    /**
     * Test to verify we can get currency list
     * 
     * @return void
     */
    public function test_can_get_subscribers()
    {
        $response = $this->getJson('/api/subscribers');
 
        $response->assertStatus(200);
       
    }

    /**
     * Test to verify we can get subscriber with valid
     * 
     * @return void
     */
    public function test_can_show_subscriber_with_valid_id()
    {
        $mockSubscriber = Subscriber::factory()->withFields()->create();
        
        $response = $this->getJson('/api/subscribers'.'/'.$mockSubscriber->id);
 
        $response->assertStatus(200);
       
    }

    /**
     * Test to cannot show subscriber with invalid id
     * 
     * @return void
     */
    public function test_cannot_show_subscriber_with_invalid_id()
    {
        // use a transaction id that doesn't exist
        $mock_subscriber_id = 10;

        $response = $this->getJson('/api/subscribers'.'/'.$mock_subscriber_id .'/');
 
        $response->assertStatus(400);
       
    }

    /**
     * Test to ensure we can create(store) a new subscriber with valid
     * 
     * @return void
     */
    public function test_can_create_subscriber_with_valid_data()
    {
        $mockData = [
            'name' => 'John Lite',
            'email' => 'john@mailerlite.com',
            'state' => Subscriber::STATE_UNCONFIRMED
        ]; 

        $response = $this->postJson('/api/subscribers', $mockData);

        $response->assertStatus(200);

    }

     /**
     * Test to ensure we cannot create(store) a new subscriber with invalid data
     * 
     * @return void
     */
    public function test_cannot_create_subscriber_with_invalid_data()
    {
        $mockData = [
            'name' => 'Company',
            'email' => 'invalid_type',
            'state' => false
        ]; 

        $response = $this->postJson('/api/subscribers', $mockData);

        $response->assertStatus(422);

    }

    /**
     * Test to ensure we can update an subscriber with valid data
     * 
     * @return void
     */
    public function test_can_update_subscriber_with_valid_data()
    {        
        $mockSubscriber = Subscriber::factory()->withFields()->create();

        $mockData = [
            'id'   => 1,
            'name' => 'John Lite',
            'email' => 'john@mailerlite.com',
            'state' => Subscriber::STATE_UNCONFIRMED
        ]; 

        $response = $this->putJson('/api/subscribers' .'/'.$mockSubscriber->id, $mockData);

        $response->assertStatus(200);

    }

     /**
     * Test to ensure we cannot update an subscriber with invalid data
     * 
     * @return void
     */
    public function test_cannot_update_subscriber_with_invalid_data()
    {
        
        $mockSubscriber = Subscriber::factory()->withFields()->create();

        $mockData = [
            'name' => 'John Lite',
            'email' => 'john@lite.com',
            'state' => 'invalid_state'
        ]; 

        $response = $this->putJson('/api/subscribers' .'/'.$mockSubscriber->id , $mockData);

        $response->assertStatus(422);

    }

    /**
     * Test to ensure we can delete subscriber with valid id
     * 
     * @return void
     */
    public function test_can_delete_subscriber_with_valid_id()
    {
        $mockSubscriber = Subscriber::factory()->withFields()->create();

        $response = $this->deleteJson('/api/subscribers'. '/'.$mockSubscriber->id);

        $response->assertStatus(200);

    }

    /**
     * Assert that the created record has been 
     * successfully stored in the database
     */
    private function assertDatabaseHasRow(Subscriber $subscriber): void
    {
        $this->assertDatabaseHas(
            'subscribers',
            [
                'name' => $subscriber->name,
                'email' => $subscriber->email,
                'state' => $subscriber->state,
            ]
        );

        foreach ($subscriber->subscribers as $subscriber) {
            $this->assertDatabaseHas(
                'subscriber_subscribers',
                [
                    'subscriber_id' => $subscriber->id,
                    'subscriber_id' => $subscriber->id,
                    'value' => $subscriber->pivot?->value ?? $subscriber->value,
                ]
            );
        }
    }

}