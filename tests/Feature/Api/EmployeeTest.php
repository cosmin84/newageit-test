<?php

namespace Tests\Feature\Api;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_employees()
    {
        Employee::factory()->count(15)->create();

        $response = $this->getJson('/api/v1/employees');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);

        $this->assertEquals(15, Employee::count());
        $this->assertCount(10, $response->json('data'));
    }

    /** @test */
    public function it_can_retrieve_a_single_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->getJson("/api/v1/employees/{$employee->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $employee->id,
                    'name' => $employee->name,
                    'email' => $employee->email,
                    'phone_number' => $employee->phone_number,
                    'job_title' => $employee->job_title,
                    'salary' => $employee->salary,
                    'created_at' => $employee->created_at->toIso8601String(),
                    'updated_at' => $employee->updated_at->toIso8601String(),
                ],
            ]);
    }

    /** @test */
    public function it_returns_not_found_when_retrieving_nonexistent_employee()
    {
        $response = $this->getJson('/api/v1/employees/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_can_create_an_employee()
    {
        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'phone_number' => '555-1234',
            'job_title' => 'Software Engineer',
            'salary' => 75000,
        ];

        $response = $this->postJson('/api/v1/employees', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'name' => 'Jane Doe',
                    'email' => 'jane.doe@example.com',
                    'phone_number' => '555-1234',
                    'job_title' => 'Software Engineer',
                    'salary' => '75000.00',
                ],
            ]);

        $this->assertDatabaseHas('employees', [
            'email' => 'jane.doe@example.com',
        ]);
    }

    /** @test */
    public function it_returns_validation_errors_when_creating_employee_with_invalid_data()
    {
        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'phone_number' => '',
            'job_title' => '',
            'salary' => -100,
        ];

        $response = $this->postJson('/api/v1/employees', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'email', 'phone_number', 'job_title', 'salary']);

        $this->assertDatabaseCount('employees', 0);
    }

    /** @test */
    public function it_can_update_an_employee()
    {
        $employee = Employee::factory()->create();

        $data = [
            'name' => 'John Smith',
            'email' => 'john.smith@example.com',
            'phone_number' => '555-5678',
            'job_title' => 'Senior Developer',
            'salary' => 90000,
        ];

        $response = $this->putJson("/api/v1/employees/{$employee->id}", $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $employee->id,
                    'name' => 'John Smith',
                    'email' => 'john.smith@example.com',
                    'phone_number' => '555-5678',
                    'job_title' => 'Senior Developer',
                    'salary' => '90000.00',
                ],
            ]);

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'email' => 'john.smith@example.com',
        ]);
    }

    /** @test */
    public function it_returns_validation_errors_when_updating_employee_with_invalid_data()
    {
        $employee = Employee::factory()->create();

        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'phone_number' => '',
            'job_title' => '',
            'salary' => -100,
        ];

        $response = $this->putJson("/api/v1/employees/{$employee->id}", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name', 'email', 'phone_number', 'job_title', 'salary']);

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'email' => $employee->email,
        ]);
    }

    /** @test */
    public function it_returns_not_found_when_updating_nonexistent_employee()
    {
        $data = [
            'name' => 'Ghost Employee',
            'email' => 'ghost@example.com',
            'phone_number' => '555-0000',
            'job_title' => 'Phantom',
            'salary' => 0,
        ];

        $response = $this->putJson('/api/v1/employees/999', $data);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /** @test */
    public function it_can_delete_an_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->deleteJson("/api/v1/employees/{$employee->id}");

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'message' => 'Employee deleted successfully.',
            ]);

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);
    }

    /** @test */
    public function it_returns_not_found_when_deleting_nonexistent_employee()
    {
        $response = $this->deleteJson('/api/v1/employees/999');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
