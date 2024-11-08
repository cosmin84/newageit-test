<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function index_page_displays_paginated_employees()
    {
        Employee::factory()->count(15)->create();

        $response = $this->get(route('employees.index'));

        $response->assertStatus(200)
            ->assertViewIs('employees.index')
            ->assertSee('Employee List')
            ->assertViewHas('employees');

        $this->assertCount(10, $response->viewData('employees'));

        $response->assertSee('Next');
    }

    /** @test */
    public function it_can_create_a_new_employee()
    {
        $data = [
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'phone_number' => '555-1234',
            'job_title' => 'Software Engineer',
            'salary' => 75000.00,
        ];

        $response = $this->post(route('employees.store'), $data);

        $response->assertRedirect(route('employees.index'))
            ->assertSessionHas('success', 'Employee created successfully.');

        $this->assertDatabaseHas('employees', [
            'email' => 'janedoe@example.com',
        ]);
    }

    /** @test */
    public function it_shows_validation_errors_when_creating_an_employee_with_invalid_data()
    {
        $data = [
            'name' => '',
            'email' => 'not-an-email',
            'phone_number' => '',
            'job_title' => '',
            'salary' => -100,
        ];

        $response = $this->post(route('employees.store'), $data);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'phone_number',
            'job_title',
            'salary',
        ]);

        $this->assertDatabaseCount('employees', 0);
    }

    /** @test */
    public function it_displays_employee_details()
    {
        $employee = Employee::factory()->create();

        $response = $this->get(route('employees.show', $employee));

        $response->assertStatus(200)
            ->assertViewIs('employees.show')
            ->assertSee($employee->name)
            ->assertSee($employee->email)
            ->assertSee($employee->phone_number)
            ->assertSee($employee->job_title)
            ->assertSee(number_format($employee->salary, 2));
    }

    /** @test */
    public function it_returns_404_when_employee_not_found()
    {
        $response = $this->get(route('employees.show', ['employee' => 999]));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_update_an_existing_employee()
    {
        $employee = Employee::factory()->create();

        $data = [
            'name' => 'John Smith',
            'email' => 'johnsmith@example.com',
            'phone_number' => '555-5678',
            'job_title' => 'Senior Developer',
            'salary' => 90000.00,
        ];

        $response = $this->put(route('employees.update', $employee), $data);

        $response->assertRedirect(route('employees.index'))
            ->assertSessionHas('success', 'Employee updated successfully.');

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name' => 'John Smith',
            'email' => 'johnsmith@example.com',
        ]);
    }

    /** @test */
    public function it_shows_validation_errors_when_updating_an_employee_with_invalid_data()
    {
        $employee = Employee::factory()->create();

        $data = [
            'name' => '',
            'email' => 'invalid-email',
            'phone_number' => '',
            'job_title' => '',
            'salary' => -500,
        ];

        $response = $this->put(route('employees.update', $employee), $data);

        $response->assertSessionHasErrors([
            'name',
            'email',
            'phone_number',
            'job_title',
            'salary',
        ]);

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name' => $employee->name,
            'email' => $employee->email,
        ]);
    }

    /** @test */
    public function it_returns_404_when_updating_nonexistent_employee()
    {
        $data = [
            'name' => 'Ghost Employee',
            'email' => 'ghost@example.com',
            'phone_number' => '555-0000',
            'job_title' => 'Phantom',
            'salary' => 0.00,
        ];

        $response = $this->put(route('employees.update', ['employee' => 999]), $data);

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_delete_an_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->delete(route('employees.destroy', $employee));

        $response->assertRedirect(route('employees.index'))
            ->assertSessionHas('success', 'Employee deleted successfully.');

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);
    }

    /** @test */
    public function it_returns_404_when_deleting_nonexistent_employee()
    {
        $response = $this->delete(route('employees.destroy', ['employee' => 999]));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_displays_the_edit_form()
    {
        $employee = Employee::factory()->create();

        $response = $this->get(route('employees.edit', $employee));

        $response->assertStatus(200)
            ->assertViewIs('employees.edit')
            ->assertSee('Edit Employee')
            ->assertSee($employee->name);
    }

    /** @test */
    public function it_returns_404_when_accessing_edit_form_of_nonexistent_employee()
    {
        $response = $this->get(route('employees.edit', ['employee' => 999]));

        $response->assertStatus(404);
    }

    /** @test */
    public function it_displays_the_create_form()
    {
        $response = $this->get(route('employees.create'));

        $response->assertStatus(200)
            ->assertViewIs('employees.create')
            ->assertSee('Add Employee');
    }

    /** @test */
    public function it_validates_search_input()
    {
        $response = $this->get(route('employees.index', ['search' => '<script>alert(1)</script>']));

        $response->assertSessionHasErrors('search');
    }

    /** @test */
    public function it_can_search_employees_by_name()
    {
        Employee::factory()->create(['name' => 'Alice Smith']);
        Employee::factory()->create(['name' => 'Bob Johnson']);

        $response = $this->get(route('employees.index', ['search' => 'Alice']));

        $response->assertStatus(200)
            ->assertSee('Alice Smith')
            ->assertDontSee('Bob Johnson');
    }

    /** @test */
    public function it_can_search_employees_by_email()
    {
        Employee::factory()->create(['email' => 'alice@example.com', 'name' => 'Alice Smith']);
        Employee::factory()->create(['email' => 'bob@example.com', 'name' => 'Bob Johnson']);

        $response = $this->get(route('employees.index', ['search' => 'bob@example.com']));

        $response->assertStatus(200)
            ->assertSee('Bob Johnson')
            ->assertDontSee('Alice Smith');
    }

    /** @test */
    public function it_can_search_employees_by_phone_number()
    {
        Employee::factory()->create(['phone_number' => '123-456-7890', 'name' => 'Alice Smith']);
        Employee::factory()->create(['phone_number' => '555-555-5555', 'name' => 'Bob Johnson']);

        $response = $this->get(route('employees.index', ['search' => '123-456-7890']));

        $response->assertStatus(200)
            ->assertSee('Alice Smith')
            ->assertDontSee('Bob Johnson');
    }

    /** @test */
    public function it_can_search_employees_by_job_title()
    {
        Employee::factory()->create(['job_title' => 'Manager', 'name' => 'Alice Smith']);
        Employee::factory()->create(['job_title' => 'Developer', 'name' => 'Bob Johnson']);

        $response = $this->get(route('employees.index', ['search' => 'Developer']));

        $response->assertStatus(200)
            ->assertSee('Bob Johnson')
            ->assertDontSee('Alice Smith');
    }

    /** @test */
    public function it_displays_no_results_message_when_search_returns_no_employees()
    {
        Employee::factory()->create(['name' => 'Alice Smith']);

        $response = $this->get(route('employees.index', ['search' => 'Nonexistent']));

        $response->assertStatus(200)
            ->assertDontSee('Alice Smith')
            ->assertSee('No employees found.');
    }

    /** @test */
    public function it_can_sort_employees_by_job_title_ascending()
    {
        Employee::factory()->create(['job_title' => 'Developer', 'name' => 'Alice Smith']);
        Employee::factory()->create(['job_title' => 'Manager', 'name' => 'Bob Johnson']);
        Employee::factory()->create(['job_title' => 'Analyst', 'name' => 'Carol Williams']);

        $response = $this->get(route('employees.index', ['sort_by' => 'job_title', 'direction' => 'asc']));

        $response->assertStatus(200);

        $employeeNames = $response->viewData('employees')->pluck('name')->toArray();

        $this->assertEquals(['Carol Williams', 'Alice Smith', 'Bob Johnson'], $employeeNames);
    }

    /** @test */
    public function it_can_sort_employees_by_salary_descending()
    {
        Employee::factory()->create(['salary' => 50000, 'name' => 'Alice Smith']);
        Employee::factory()->create(['salary' => 75000, 'name' => 'Bob Johnson']);
        Employee::factory()->create(['salary' => 60000, 'name' => 'Carol Williams']);

        $response = $this->get(route('employees.index', ['sort_by' => 'salary', 'direction' => 'desc']));

        $response->assertStatus(200);

        $employeeNames = $response->viewData('employees')->pluck('name')->toArray();

        $this->assertEquals(['Bob Johnson', 'Carol Williams', 'Alice Smith'], $employeeNames);
    }

    /** @test */
    public function it_shows_validation_error_when_sort_by_parameter_is_invalid()
    {
        $response = $this->get(route('employees.index', ['sort_by' => 'invalid_column']));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['sort_by']);
    }

    /** @test */
    public function it_shows_validation_error_when_direction_parameter_is_invalid()
    {
        $response = $this->get(route('employees.index', ['direction' => 'invalid_direction']));

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['direction']);
    }

    /** @test */
    public function it_can_search_and_sort_employees_together()
    {
        Employee::factory()->create(['name' => 'Alice Smith', 'job_title' => 'JavaScript Developer']);
        Employee::factory()->create(['name' => 'Bob Johnson', 'job_title' => 'Analyst']);
        Employee::factory()->create(['name' => 'Carol Williams', 'job_title' => 'PHP Developer']);

        $response = $this->get(route('employees.index', [
            'search' => 'Developer',
            'sort_by' => 'job_title',
            'direction' => 'desc',
        ]));

        $response->assertStatus(200);

        $employeeNames = $response->viewData('employees')->pluck('name')->toArray();

        $this->assertEquals(['Carol Williams', 'Alice Smith'], $employeeNames);

        $response->assertDontSee('Bob Johnson');
    }

    /** @test */
    public function it_prevents_creating_employee_with_duplicate_email()
    {
        Employee::factory()->create(['email' => 'duplicate@example.com']);

        $data = [
            'name' => 'New Employee',
            'email' => 'duplicate@example.com',
            'phone_number' => '555-9999',
            'job_title' => 'Tester',
            'salary' => 50000.00,
        ];

        $response = $this->post(route('employees.store'), $data);

        $response->assertSessionHasErrors(['email']);

        $this->assertDatabaseCount('employees', 1);
    }

    /** @test */
    public function it_prevents_updating_employee_email_to_another_employees_email()
    {
        $employee1 = Employee::factory()->create(['email' => 'employee1@example.com']);
        $employee2 = Employee::factory()->create(['email' => 'employee2@example.com']);

        $data = [
            'name' => $employee1->name,
            'email' => 'employee2@example.com',
            'phone_number' => $employee1->phone_number,
            'job_title' => $employee1->job_title,
            'salary' => $employee1->salary,
        ];

        $response = $this->put(route('employees.update', $employee1), $data);

        $response->assertSessionHasErrors(['email']);

        $this->assertDatabaseHas('employees', [
            'id' => $employee1->id,
            'email' => 'employee1@example.com',
        ]);
    }
}
