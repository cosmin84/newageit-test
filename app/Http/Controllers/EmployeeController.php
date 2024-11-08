<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeSearchRequest;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index(EmployeeSearchRequest $request)
    {
        $search = $request->input('search');
        $sortBy = $request->filled('sort_by') ? $request->input('sort_by') : 'name';
        $direction = $request->input('direction', 'asc');

        $employees = Employee::query()
            ->select('id', 'name', 'email', 'phone_number', 'job_title', 'salary')
            ->search($search)
            ->sortBy($sortBy, $direction)
            ->paginate(10);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(StoreEmployeeRequest $request)
    {
        Employee::create($request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        $employee->update($request->validated());

        return redirect()->route('employees.index')
            ->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();

        return redirect()->route('employees.index')
            ->with('success', 'Employee deleted successfully.');
    }
}
