<?php

use App\Http\Controllers\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::resource('employees', EmployeeController::class);

Route::get('/employees-vuejs', function () {
    return view('employees-vuejs.index');
});
