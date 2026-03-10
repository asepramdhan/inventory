<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::livewire('/dashboard', 'pages::dashboard.index')->name('dashboard.index');

Route::livewire('/users', 'pages::users.index')->name('users.index');
Route::livewire('/users/create', 'pages::users.create')->name('users.create');
Route::livewire('/users/{user}/edit', 'pages::users.edit')->name('users.edit');

Route::livewire('/products', 'pages::products.index')->name('products.index');
Route::livewire('/products/create', 'pages::products.create')->name('products.create');
Route::livewire('/products/{product}/edit', 'pages::products.edit')->name('products.edit');

Route::livewire('/stores', 'pages::stores.index')->name('stores.index');
Route::livewire('/stores/create', 'pages::stores.create')->name('stores.create');
Route::livewire('/stores/{store}/edit', 'pages::stores.edit')->name('stores.edit');

Route::livewire('/settings', 'pages::settings.index')->name('settings.index');
