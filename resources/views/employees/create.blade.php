@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Add Employee</h1>

    <form action="{{ route('employees.store') }}" method="POST">
        @csrf
        @include('employees.partials.form', ['buttonText' => 'Create'])
    </form>
@endsection
