<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Home;
use App\Livewire\AboutUs;
use App\Livewire\Publication;
use App\Livewire\Cours;
use App\Livewire\Contact;

Route::get('/', Home::class)->name('home');

// Define routes for the portfolio sections
Route::get('/about-us', AboutUs::class)->name('about-us');
Route::get('/publications', Publication::class)->name('publications');
Route::get('/courses', Cours::class)->name('courses');
Route::get('/contact', Contact::class)->name('contact');

