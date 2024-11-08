@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6">Edit Employee</h1>

    <form action="{{ route('employees.update', $employee) }}" method="POST">
        @csrf
        @method('PUT')
        @include('employees.partials.form', ['buttonText' => 'Update'])
    </form>
@endsection
