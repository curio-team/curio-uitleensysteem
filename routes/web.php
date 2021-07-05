<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', [ProductController::class, 'searchProducts'])->name('searchProducts');

Route::get('/reserveren/{productId}', [ProductController::class, 'reserveProduct'])->name('reserveProduct');
Route::post('/reserveren/{productId}', [ProductController::class, 'processReserveProduct'])->name('processReserveProduct');

Route::get('/retour/{productId}', [ProductController::class, 'returnProduct'])->name('returnProduct');
Route::get('/retour/{productId}/process', [ProductController::class, 'processReturnProduct'])->name('processReturnProduct');

Route::get('/admin/producten', [ProductController::class, 'manageProducts'])->name('manageProducts')->middleware('auth');
Route::get('/admin/producten/toevoegen', [ProductController::class, 'createProduct'])->name('createProduct')->middleware('auth');
Route::post('/admin/producten/toevoegen', [ProductController::class, 'processCreateProduct'])->name('processCreateProduct')->middleware('auth');

Route::get('/admin/producten/{productId}', [ProductController::class, 'manageProduct'])->name('manageProduct')->middleware('auth');
Route::get('/admin/producten/{productId}/aanpassen', [ProductController::class, 'editProduct'])->name('editProduct')->middleware('auth');
Route::post('/admin/producten/{productId}/aanpassen', [ProductController::class, 'processEditProduct'])->name('processEditProduct')->middleware('auth');
Route::get('/admin/producten/{productId}/verwijderen', [ProductController::class, 'processDeleteProduct'])->name('processDeleteProduct')->middleware('auth');

Route::get('/admin/reserveringen', [ReservationController::class, 'listReservations'])->name('listReservations')->middleware('auth');
Route::get('/admin/reserveringen/{reservationId}', [ReservationController::class, 'manageReservation'])->name('manageReservation')->middleware('auth');
Route::post('/admin/reserveringen/{reservationId}', [ReservationController::class, 'updateReservation'])->name('updateReservation')->middleware('auth');

Route::get('/admin/types', [ProductTypeController::class, 'manageProductTypes'])->name('manageProductTypes')->middleware('auth');
Route::get('/admin/types/toevoegen', [ProductTypeController::class, 'createProductType'])->name('createProductType')->middleware('auth');
Route::post('/admin/types/toevoegen', [ProductTypeController::class, 'processCreateProductType'])->name('processCreateProductType')->middleware('auth');
Route::get('/admin/types/{productTypeId}/aanpassen', [ProductTypeController::class, 'editProductType'])->name('editProductType')->middleware('auth');
Route::post('/admin/types/{productTypeId}/aanpassen', [ProductTypeController::class, 'processEditProductType'])->name('processEditProductType')->middleware('auth');
Route::get('/admin/types/{productTypeId}/verwijderen', [ProductTypeController::class, 'processDeleteProductType'])->name('processDeleteProductType')->middleware('auth');

Route::get('/admin/studenten/{studentId}', [StudentController::class, 'showStudent'])->name('showStudent')->middleware('auth');

Route::get('/admin/import', [ImportController::class, 'listImports'])->name('import')->middleware('auth');
Route::post('/admin/import/product', [ImportController::class, 'processProductImport'])->name('processProductImport')->middleware('auth');
Route::get('/admin/import/studenten', [ImportController::class, 'processStudentImport'])->name('processStudentImport')->middleware('auth');

// AJAX routes
Route::get('/find-products', [ProductController::class, 'findProducts'])->name('findProducts');
Route::get('/find-manage-products', [ProductController::class, 'findManageProducts'])->name('findManageProducts');
Route::get('/find-manage-product-types', [ProductTypeController::class, 'findManageProductTypes'])->name('findManageProductTypes');
Route::get('/find-reservations', [ReservationController::class, 'findReservations'])->name('findReservations');

// Auth routes

Route::get('/login', function(){return redirect('/amoclient/redirect');})->name('login');
Route::get('/amoclient/ready', function(){return redirect()->route('manageProducts');});

Route::get('/logout', function(){return redirect('/amoclient/logout');})->name('logout');

Route::get('/register', function() {return abort(404);});
Route::post('/register', function() {return abort(404);});

Route::get('/admin/new-user', [RegisterController::class, 'showRegistrationForm'])->name('newUser')->middleware('auth');
Route::post('/admin/new-user', [RegisterController::class, 'register'])->name('processNewUser')->middleware('auth');