<?php

use App\Http\Controllers\UserController as ControllersUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UserController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::post('/{user}/post-request', [ControllersUserController::class, 'postRequest'])->name('postRequest');
Route::get('/{user}/tambah-product', [ControllersUserController::class, 'handleRequest'])->name('form_product');
Route::get('/products', [ControllersUserController::class, 'getProduct'])->name('get_product');
Route::get('/{user}/product/{product}', [ControllersUserController::class, 'editProduct'])->name('edit_product');
Route::put('/{user}/product/{product}/update', [ControllersUserController::class, 'updateProduct'])->name('update_product');
Route::post('/{user}/product/{product}/delete', [ControllersUserController::class, 'deleteProduct'])->name('delete_product');
Route::get('/profile/{user}', [ControllersUserController::class, 'getProfile'])->name('get_profile');

Route::get('/admin/{user}/list-products', [ControllersUserController::class, 'getAdmin'])->name('admin_page');
