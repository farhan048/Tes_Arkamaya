<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\clientController;
use App\Http\Controllers\projectController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['prefix' => 'client', 'middleware' => ['auth']], function () {
    Route::get('/',[clientController::class,'index'])->name('client');
    Route::post('/store',[clientController::class,'store'])->name('client.store');
    Route::get('/edit/{id}',[clientController::class,'edit'])->name('client.edit');
    Route::put('/update/{id}',[clientController::class,'update'])->name('client.update');
    Route::delete('/destroy/{id}',[clientController::class,'destroy'])->name('client.delete');
});
Route::group(['prefix' => 'project', 'middleware' => ['auth']], function () {
    
    Route::get('/',[projectController::class,'index'])->name('project');
    Route::get('/show',[projectController::class,'show'])->name('project.show');
    Route::post('/store',[projectController::class,'store'])->name('project.store');
    Route::get('/edit/{id}',[projectController::class,'edit'])->name('project.edit');
    Route::put('/update/{id}',[projectController::class,'update'])->name('project.update');
    Route::delete('/destroy/{id}',[projectController::class,'destroy'])->name('project.delete');
    Route::get('/project/remove}',[projectController::class,'remove'])->name('project.multi-delete');
});
Route::get('/', function () {
    return view('welcome');
});
Route::get('/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
