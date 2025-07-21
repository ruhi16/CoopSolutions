<?php

use App\Http\Livewire\Ec01Organisation;
use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::group(
    ['prefix' => 'admin', 'middleware' => ['web']],
    function () {
        Route::get('/organisation', Ec01Organisation::class)->name('organisation');
        

    }
);



require __DIR__.'/auth.php';
