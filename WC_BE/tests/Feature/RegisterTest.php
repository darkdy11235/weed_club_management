<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        // Arrange
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            // Add other necessary fields
        ];

        // Act
        $response = $this->post('/api/register', $userData);

        // Assert
        $response->assertStatus(201); // Assuming successful registration returns 201 status

        // Ensure the user is in the database
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email'],
        ]);

        // Verify that the password is hashed in the database
        $this->assertDatabaseHas('users', [
            'password' => Hash::make($userData['password']),
        ]);
    }
}
