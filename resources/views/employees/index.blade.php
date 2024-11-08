@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Employee List</h1>



    <form method="GET" action="{{ route('employees.index') }}" class="flex space-x-4 mb-6">
        <input type="text" name="search" placeholder="Search by name, email, phone, or job title"
               class="px-4 py-2 border rounded w-1/3"
               value="{{ request('search') }}">

        <select name="sort_by" class="px-4 py-2 border rounded">
            <option value="">Sort By</option>
            <option value="job_title" {{ request('sort_by') == 'job_title' ? 'selected' : '' }}>Job Title</option>
            <option value="salary" {{ request('sort_by') == 'salary' ? 'selected' : '' }}>Salary</option>
        </select>

        <select name="direction" class="px-4 py-2 border rounded">
            <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Ascending</option>
            <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Descending</option>
        </select>

        <button class="px-4 py-2 bg-blue-500 text-white rounded">Apply</button>
        <a href="{{ route('employees.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded">Reset</a>
    </form>

    <a href="{{ route('employees.create') }}" class="mb-4 inline-block px-4 py-2 bg-green-500 text-white rounded">Add Employee</a>

    @if(session('success'))
        <x-alert type="success">
            {{ session('success') }}
        </x-alert>
    @endif

    @if($errors->any())
        <div class="mb-4">
            <x-alert type="error">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </x-alert>
        </div>
    @endif

    @if($employees->count())
        <table class="w-full bg-white rounded shadow">
            <thead>
            <tr class="bg-gray-200 text-left">
                <th class="px-4 py-2">Name</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Phone Number</th>
                <th class="px-4 py-2">Job Title</th>
                <th class="px-4 py-2">Salary</th>
                <th class="px-4 py-2" width="200">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($employees as $employee)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $employee->name }}</td>
                    <td class="px-4 py-2">{{ $employee->email }}</td>
                    <td class="px-4 py-2">{{ $employee->phone_number }}</td>
                    <td class="px-4 py-2">{{ $employee->job_title }}</td>
                    <td class="px-4 py-2">${{ number_format($employee->salary, 2) }}</td>
                    <td class="px-4 py-2 flex space-x-2">
                        <a href="{{ route('employees.show', $employee) }}" class="px-2 py-1 bg-blue-500 text-white rounded text-sm">View</a>
                        <a href="{{ route('employees.edit', $employee) }}" class="px-2 py-1 bg-yellow-500 text-white rounded text-sm">Edit</a>
                        <form action="{{ route('employees.destroy', $employee) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2 py-1 bg-red-500 text-white rounded text-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="mt-4">
            {{ $employees->withQueryString()->links() }}
        </div>
    @else
        <p class="mt-4">No employees found.</p>
    @endif
@endsection
