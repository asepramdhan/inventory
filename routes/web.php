<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::livewire('/dashboard', 'pages::dashboard.index')->name('dashboard.index');
Route::livewire('/users', 'pages::users.index')->name('users.index');
Route::livewire('/users/create', 'pages::users.create')->name('users.create');
Route::livewire('/users/{user}/edit', 'pages::users.edit')->name('users.edit');
