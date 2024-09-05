<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role; // Assuming you have a Role model
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class RoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that an admin can create a vehicle.
     */
    public function test_admin_can_create_vehicle()
    {
        $admin = User::factory()->create(['role_id' => 1]);

        // Create JWT token for the admin
        $token = JWTAuth::fromUser($admin);

        // Vehicle data
        $vehicleData = [
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2020,
            'license_plate' => 'ABC1234',
        ];

        // Act as admin and send POST request to create vehicle with JWT token
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->post('/vehicles', $vehicleData);

        // Assert that the vehicle was successfully created
        $response->assertStatus(201);

        // Verify that the vehicle exists in the database
        $this->assertDatabaseHas('vehicles', [
            'make' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2020,
            'license_plate' => 'ABC1234',
        ]);
    }

    /**
     * Test that a driver cannot create a vehicle.
     */
    public function test_driver_cannot_create_vehicle()
    {
        // Create a driver user with role_id 2
        $driver = User::factory()->create(['role_id' => 2]);

        // Create JWT token for the driver
        $token = JWTAuth::fromUser($driver);

        // Vehicle data
        $vehicleData = [
            'make' => 'Honda',
            'model' => 'Civic',
            'year' => 2019,
            'license_plate' => 'XYZ5678',
        ];

        // Act as driver and send POST request to create vehicle with JWT token
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
                         ->post('/vehicles', $vehicleData);

        // Assert that the request was forbidden
        $response->assertStatus(403);

        // Verify that the vehicle was not added to the database
        $this->assertDatabaseMissing('vehicles', [
            'make' => 'Honda',
            'model' => 'Civic',
            'year' => 2019,
            'license_plate' => 'XYZ5678',
        ]);
    }
}
