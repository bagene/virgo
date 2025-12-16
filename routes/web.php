<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
});
Route::get('/{slug}', function () {
    return view('app');
});
