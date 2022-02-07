<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCreation(): void
    {
        $user = new User();
        $user->setEmail("test@test.com");
        $user->setFirstName("Test First");
        $user->setLastName("Test Last");

        $this->assertEquals("test@test.com", $user->getEmail());
        $this->assertEquals("Test First", $user->getFirstName());
        $this->assertEquals("Test Last", $user->getLastName());
    }
}