<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    public function testGetUsers()
    {
        $client = static::createClient();
        $client->request("GET", "/users");
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

    public function testCreateError()
    {
        $client = static::createClient();
        $client->jsonRequest("POST", "/users");
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
    }

    public function testCreateEmailError()
    {
        $client = static::createClient();
        $client->jsonRequest("POST", "/users", [
            'email' => "rafael",
            'firstName' => "Rafael",
            "lastName" => "Custodio"
        ]);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $json = json_decode($client->getResponse()->getContent(), JSON_OBJECT_AS_ARRAY);
        $this->assertCount(1, $json['errors']);
        $this->assertEquals('email', array_keys($json['errors'][0])[0]);
    }

    public function testGetNotFoundUser()
    {
        $client = static::createClient();
        $client->request("GET", "/users/0");

        $this->assertEquals(Response::HTTP_NOT_FOUND, $client->getResponse()->getStatusCode());
    }
}