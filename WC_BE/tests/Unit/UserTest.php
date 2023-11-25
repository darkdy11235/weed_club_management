<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_user()
    {
        // Arrange
        $user = User::factory()->create();

        // Assert
        $this->assertInstanceOf(User::class, $user);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $user->name,
            'age' => $user->age,
            'gender' => $user->gender,
            'phone' => $user->phone,
            'address' => $user->address,
            'email' => $user->email,
            'created_by' => $user->created_by,
            'status' => $user->status,
        ]);
    }

    public function test_can_update_user()
    {
        // Arrange
        $user = User::factory()->create();

        $newData = [
            'name' => 'Updated Name',
            'email' => 'updated@gmail.com',
            'age' => 30,

            // Add other attributes to update
        ];

        // Act
        $user->update($newData);

        // Refresh the model to get the updated attributes from the database
        $user = $user->fresh();

        // Assert
        $this->assertEquals($newData['name'], $user->name);
        $this->assertEquals($newData['email'], $user->email);
        $this->assertEquals($newData['age'], $user->age);

        // Check other updated fields
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => $newData['name'],
            'email' => $newData['email'],
            'age' => $newData['age'],
            // Add other attributes to check
        ]);

        // $this->assertDatabaseMissing('users', [
        //     'email' => $newData->email
        //     // Add other attributes to check
        // ]);
    }

    public function test_can_delete_user()
    {
        // Arrange
        $user = User::factory()->create();
        // Act
        $user->delete();
        // Assert
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => $user->name,
            'age' => $user->age,
            'gender' => $user->gender,
            'phone' => $user->phone,
            'address' => $user->address,
            'email' => $user->email,
            'created_by' => $user->created_by,
            'status' => $user->status,
        ]);
    }

    // Add more test cases for other model functionality
}
