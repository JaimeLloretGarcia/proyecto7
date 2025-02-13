<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Order;
use App\Models\Customer;
use Laravel\Sanctum\Sanctum;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrdersCustomerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_OrdersCustomers()
    {
        $users = User::factory(2)
        ->has(Customer::factory()
            ->has(Order::factory()->count(3))
            ->count(1))
        ->create();
    Sanctum::actingAs($users[1]);
    $response = $this->get('/api/orders');

    $response->dumpHeaders();

    $response->dump();

    $response->assertStatus(200)
        ->assertJsonStructure(['data' => ['*' => ['id', 'attributes' => ['id', 'customer_id', 'amount']]]])
        ->assertJsonCount(3, 'data');
    }
}
