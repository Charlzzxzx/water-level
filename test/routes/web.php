<?php

use App\Livewire\Maps;
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

Route::get('/', Maps::class)->name('Lumbayao');
Route::post('/', [Maps::class,'store']);
Route::get('/reload', [Maps::class,'hotReload']);