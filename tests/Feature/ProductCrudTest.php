<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ProductCrudTest extends TestCase
{
    use RefreshDatabase;

    protected  $token;

    public function setUp():void
    {
        parent::setUp();
        $user = User::factory()->create();
        $response = $this->postJson('/api/admin/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->token = $response->json('data.token');
    }
    public function test_getting_items(): void
    {
        Product::factory()->amazon()->count(3)->create();
        Product::factory()->example()->count(4)->create();
        Product::factory()->steam()->count(1)->create();

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/admin/products');

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('data')->etc();
            $json->has('data.0', function (AssertableJson $json) {
                $json
                    ->whereType('id', 'integer')
                    ->whereType('name', 'string')
                    ->whereType('url', 'string')
                    ->whereType('price', 'integer')
                    ->whereType('description', 'string')
                    ->whereType('created_at', 'string');

            });
        });
    }

    public function test_getting_single_item(): void
    {
        $attributes = [
            'name' => 'Test item',
            'price' => 12300.45,
            'url' => 'https://example.store/876446446',
            'description' => 'Test description',
        ];

        $item = Product::factory()->create($attributes);

        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/admin/product/'.$item->id);

        $response->assertStatus(200);

        $responseItem = $response->json()['data'];

        $this->assertSame($item->id, $responseItem['id']);
        $this->assertSame($attributes['name'], $responseItem['name']);
        $this->assertSame(12300.45, $responseItem['price']);
        $this->assertSame($attributes['url'], $responseItem['url']);
        $this->assertSame($attributes['description'], $responseItem['description']);
    }

    public function test_creating_new_item_with_valid_data(): void
    {
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/admin/product', [
            'name' => 'New item',
            'price' => 12345,
            'url' => 'https://store.example.com/my-product',
            'description' => 'Test **item** description',
        ]);

        $this->assertSame('New item', $response->json()['data']['name']);

        $this->assertDatabaseHas(Product::class, [
            'name' => 'New item',
            'price' => 12345,
            'url' => 'https://store.example.com/my-product',
            'description' => "<p>Test <strong>item</strong> description</p>\n",
        ]);
    }

    public function test_creating_new_item_with_invalid_data(): void
    {
        $response =  $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->postJson('/api/admin/product', [
            'name' => 'New item',
            'price' => 'string',
            'url' => 'invalid url',
            'description' => 'Test item description',
        ]);

        $response->assertStatus(422);
    }

    public function test_updating_item_with_valid_data(): void
    {
        $item = Product::factory()->create();

        $response =  $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson('/api/admin/product/ '.$item->id, [
            'name' => 'Updated title',
            'price' => $item->price,
            'url' => 'https://store.example.com/my-other-product',
            'description' => 'Test description',
        ]);

        $this->assertSame('Updated title', $response->json()['data']['name']);
        // $this->assertSame(
        //     'Test description',
        //     $response->json()['data']['description']
        // );

        $this->assertDatabaseHas(Product::class, [
            'id' => $item->id,
            'name' => 'Updated title',
            'price' => $item->price,
            'url' => 'https://store.example.com/my-other-product',
            'description' => "<p>Test description</p>\n",
        ]);
    }

    public function test_updating_item_with_invalid_data(): void
    {
        $item = Product::factory()->create();

        $response =  $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->putJson('/api/admin/product/ '.$item->id, [
            'name' => 'Updated title',
            'price' => $item->price,
            'url' => 'invalid url',
            'description' => 'Test item description',
        ]);

        $response->assertStatus(422);
    }

    public function test_total_products()
    {
        Product::factory()->amazon()->count(3)->create();
        Product::factory()->example()->count(4)->create();
        Product::factory()->steam()->count(1)->create();
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/admin/products/total_products');

        $response->assertStatus(200);
        $this->assertEquals(8,$response->json()['data']);
    }

    public function test_total_urls_count_for_each_website()
    {
        Product::factory()->amazon()->count(3)->create();
        Product::factory()->example()->count(4)->create();
        Product::factory()->steam()->count(1)->create();
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/admin/products/total_urls_count_for_each_website');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
        $response->assertJsonFragment([
            "domain" => "amazon"
        ]);
        $response->assertJsonFragment([
            "domain" => "example"
        ]);
        $response->assertJsonFragment([
            "domain" => "steampowered"
        ]);
       // $this->assertEquals(8,$response->json()['data']);
    }

    public function test_avg_price_products()
    {
        Product::factory()->amazon()->count(3)->create();
        Product::factory()->example()->count(4)->create();
        Product::factory()->steam()->count(1)->create();
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/admin/products/avg_price_products');

        $response->assertStatus(200);
        $this->assertIsString($response->json()['data']);
    }

    public function test_webiste_highest_total_prices()
    {
        Product::factory()->amazon()->count(3)->create();
        Product::factory()->example()->count(4)->create();
        Product::factory()->steam()->count(1)->create();
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/admin/products/webiste_highest_total_prices');

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('msg')
                 ->has('statusMsg')
                 ->has('data', function (AssertableJson $json) {
                    $json
                        ->whereType('domain', 'string')
                        ->whereType('total_price', 'string');
                });
        });
    }

    public function testtotal_price_during_month()
    {
        Product::factory()->amazon()->count(3)->create();
        Product::factory()->example()->count(4)->create();
        Product::factory()->steam()->count(1)->create();
        $response = $this->withHeader('Authorization', 'Bearer ' . $this->token)
        ->getJson('/api/admin/products/total_price_during_month');

        $response->assertStatus(200);
        $this->assertIsString($response->json()['data']);
    }

}
