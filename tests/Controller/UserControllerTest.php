<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testGetUsers()
    {
        $client = static::createClient();
        $client->request("GET", "/users");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateError()
    {
        $client = static::createClient();
        $client->jsonRequest("POST", "/users");
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testCreateEmailError()
    {
        $client = static::createClient();
        $client->jsonRequest("POST", "/users", [
            'email' => "rafael",
            'firstName' => "Rafael",
            "lastName" => "Custodio"
        ]);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $json = json_decode($client->getResponse()->getContent(), JSON_OBJECT_AS_ARRAY);
        $this->assertCount(1, $json['violations']);
        $this->assertEquals('email', $json['violations'][0]['propertyPath']);
    }

    public function testGetNotFoundUser()
    {
        $client = static::createClient();
        $client->request("GET", "/users/0");

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}