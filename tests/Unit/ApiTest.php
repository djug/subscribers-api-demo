<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Subscriber;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    private $apiKey;

    public function setUp()
    {
        $this->apiKey = "my-api-key";
        parent::setUp();
        User::create([
                    'name' =>"testName",
                    'email' => 'test@example.com',
                    'password' => \Hash::make('password'),
                    'api_key' => $this->apiKey
                ]);
    }

    public function tearDown()
    {
        $this->beforeApplicationDestroyed(function () {
            \DB::disconnect();
        });

        parent::tearDown();
    }
    /**
     * @test
     */
    public function it_returns_an_error_response_if_apiKey_is_wrong()
    {
        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' => 'WRONG_API_KEY',

                ])->json('GET', 'api/subscribers');

        $response->assertStatus(302);
        $response->assertJson(['error' => ['code' => 302, 'message' => "API-Key Unauthorized"]]);
    }

    /**
     * @test
     */
    public function it_returns_200_if_ApiKey_is_correct()
    {
        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('GET', 'api/subscribers');
        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_can_create_subscriber()
    {
        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('POST', 'api/subscribers', ["email" => "contact@youghourta.com", "name" => "Youghourta"]);

        $data  = $response->getData();
        $response->assertStatus(201);
        $this->assertEquals($data->email, 'contact@youghourta.com');
        $this->assertEquals($data->name, 'Youghourta');
        $this->assertEmpty($data->fields);
    }

    /**
     * @test
     */
    public function it_returns_error_message_if_email_format_is_wrong()
    {
        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('POST', 'api/subscribers', ["email" => "youghourtaAcom", "name" => "Youghourta"]);

        $data  = $response->getData();

        $response->assertStatus(400);
    }

    /**
     * @test
     */
    public function it_returns_error_message_if_email_host_domain_is_not_active()
    {
        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('POST', 'api/subscribers', ["email" => "contact@this-is-not-youghourta-website.com", "name" => "Youghourta"]);

        $data  = $response->getData();
        $response->assertStatus(400);
    }

    /**
     * @test
     */
    public function it_can_update_a_subscriber()
    {
        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('POST', 'api/subscribers', ["email" => "contact@youghourta.com", "name" => "Youghourta"]);

        $data  = $response->getData();
        $this->assertEquals($data->email, "contact@youghourta.com");
        $this->assertEquals($data->name, "Youghourta");
        $userId = $data->id;

        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('PUT', "api/subscribers/{$userId}", ["name" => "djug"]);
        $data = $response->getData();
        $response->assertStatus(200);
        $this->assertEquals($data->name, "djug");
    }

    /**
     * @test
     */
    public function it_can_update_a_subscriber_searched_by_its_email_address()
    {
        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('POST', 'api/subscribers', ["email" => "contact@youghourta.com", "name" => "Youghourta", 'state' => 'active']);


        $data  = $response->getData();
        $this->assertEquals($data->state, "active");
        $this->assertEquals($data->email, "contact@youghourta.com");

        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('PUT', "api/subscribers/contact@youghourta.com", ["state" => "junk"]);
        $data = $response->getData();

        $response->assertStatus(200);
        $this->assertEquals($data->state, "junk");
    }

    /**
     * @test
     */
    public function it_returns_an_error_message_when_updating_subscriber_with_invalid_data()
    {

        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('POST', 'api/subscribers', ["email" => "contact@youghourta.com", "name" => "Youghourta", 'state' => 'active']);

        $data  = $response->getData();
        $this->assertEquals($data->state, "active");

        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('PUT', "api/subscribers/contact@youghourta.com", ["state" => "INVALID-STATE"]);
        $response->assertStatus(400);

    }

    /**
     * @test
     */
    public function it_can_create_fields()
    {

        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('POST', 'api/fields', ["title" => "city", "type" => "string"]);

        $data  = $response->getData();

        $this->assertEquals($data->title, "city");
        $this->assertEquals($data->type, "string");

    }

    /**
     * @test
     */
    public function it_can_update_fields()
    {

        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('POST', 'api/fields', ["title" => "city", "type" => "string"]);

        $data  = $response->getData();

        $this->assertEquals($data->title, "city");
        $this->assertEquals($data->type, "string");
        $fieldId = $data->id;
        $response2 = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('PUT', "api/fields/{$fieldId}", ["title" => "age", "type" => "number"]);

        $data  = $response2->getData();

        $this->assertEquals($data->title, "age");
        $this->assertEquals($data->type, "number");
    }

    /**
     * @test
     */
    public function it_can_create_subscriber_with_fields()
    {
        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,
                ])->json('POST', 'api/fields', ["title" => "city", "type" => "string"]);

        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('POST', 'api/subscribers', ["email" => "contact@youghourta.com", "name" => "Youghourta", "fields" => ['city' => "Ain Oussera"]]);

        $data  = $response->getData();
        $field = $data->fields[0];

        $response->assertStatus(201);
        $this->assertEquals($field->title, 'city');
        $this->assertEquals($field->value, 'Ain Oussera');
        $this->assertEquals($field->type, 'STRING');

    }

    /**
     * @test
     */
    public function it_doenst_add_fields_to_subscriber_if_fields_were_not_accepted()
    {

        $response = $this->withHeaders([
                    'X-MailerLite-ApiKey' =>  $this->apiKey,

                ])->json('POST', 'api/subscribers', ["email" => "contact@youghourta.com", "name" => "Youghourta", "fields" => ['city' => "Ain Oussera"]]);

        $data  = $response->getData();

        $this->assertEmpty($data->fields);

    }


}
