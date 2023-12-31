<?php

use App\Http\Livewire\ApplicantRegisterComponent;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', ApplicantRegisterComponent::class)->name('register.step1')->name('home');

Route::get('/success', function () {
    return view('register.success');
})->name('register.success');
