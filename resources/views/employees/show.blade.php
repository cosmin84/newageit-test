@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Employee Details</h1>

    <div class="bg-white p-6 rounded shadow">
        <p><strong>Name:</strong> {{ $employee->name }}</p>
        <p><strong>Email:</strong> {{ $employee->email }}</p>
        <p><strong>Phone Number:</strong> {{ $employee->phone_number }}</p>
        <p><strong>Job Title:</strong> {{ $employee->job_title }}</p>
        <p><strong>Salary:</strong> ${{ number_format($employee->salary, 2) }}</p>
    </div>

    <div class="mt-4">
        <a href="{{ route('employees.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Back to List</a>
        <a href="{{ route('employees.edit', $employee) }}" class="px-4 py-2 bg-blue-500 text-white rounded">Edit</a>
    </div>
@endsection
