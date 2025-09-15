<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('emails.reservation-created');
});


Route::get('/redis-test', function () {
    try {
        \Illuminate\Support\Facades\Redis::set('test', 'funciona');
        return 'Redis funcionando';
    } catch (\Exception $e) {
        return 'Error: ' . $e->getMessage();
    }
});
