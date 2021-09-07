<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DeviceRegisterController;
use App\Http\Controllers\TeacherController;
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

Route::get('/reserveren/{productId}', [ProductController::class, 'reserveProduct'])->middleware('registered.device.check')->name('reserveProduct');
Route::post('/reserveren/{productId}', [ProductController::class, 'processReserveProduct'])->middleware('registered.device.check')->name('processReserveProduct');

Route::get('/retour/{productId}', [ProductController::class, 'returnProduct'])->middleware('registered.device.check')->name('returnProduct');
Route::get('/retour/{productId}/process', [ProductController::class, 'processReturnProduct'])->middleware('registered.device.check')->name('processReturnProduct');

Route::get('/admin/producten', [ProductController::class, 'manageProducts'])->name('manageProducts')->middleware('auth');
Route::get('/admin/producten/toevoegen', [ProductController::class, 'createProduct'])->name('createProduct')->middleware('auth');
Route::post('/admin/producten/toevoegen', [ProductController::class, 'processCreateProduct'])->name('processCreateProduct')->middleware('auth');

Route::get('/admin/producten/{productId}', [ProductController::class, 'manageProduct'])->name('manageProduct')->middleware('auth');
Route::get('/admin/producten/{productId}/aanpassen', [ProductController::class, 'editProduct'])->name('editProduct')->middleware('auth');
Route::post('/admin/producten/{productId}/aanpassen', [ProductController::class, 'processEditProduct'])->name('processEditProduct')->middleware('auth');
Route::get('/admin/producten/{productId}/archiveren', [ProductController::class, 'processArchiveProduct'])->name('processArchiveProduct')->middleware('auth');

Route::get('/admin/reserveringen', [ReservationController::class, 'listReservations'])->name('listReservations')->middleware('auth');
Route::get('/admin/reserveringen/{reservationId}', [ReservationController::class, 'manageReservation'])->name('manageReservation')->middleware('auth');
Route::post('/admin/reserveringen/{reservationId}', [ReservationController::class, 'updateReservation'])->name('updateReservation')->middleware('auth');
Route::post('/admin/reserveringen/{reservationId}/verlengen', [ReservationController::class, 'extendReservation'])->name('extendReservation')->middleware('auth');
Route::get('/admin/reserveringen/{reservationId}/oneindig-verlengen', [ReservationController::class, 'extendReservationIndefinitely'])->name('extendReservationIndefinitely')->middleware('auth');



Route::get('/admin/types', [ProductTypeController::class, 'manageProductTypes'])->name('manageProductTypes')->middleware('auth');
Route::get('/admin/types/toevoegen', [ProductTypeController::class, 'createProductType'])->name('createProductType')->middleware('auth');
Route::post('/admin/types/toevoegen', [ProductTypeController::class, 'processCreateProductType'])->name('processCreateProductType')->middleware('auth');
Route::get('/admin/types/{productTypeId}/aanpassen', [ProductTypeController::class, 'editProductType'])->name('editProductType')->middleware('auth');
Route::post('/admin/types/{productTypeId}/aanpassen', [ProductTypeController::class, 'processEditProductType'])->name('processEditProductType')->middleware('auth');
Route::get('/admin/types/{productTypeId}/verwijderen', [ProductTypeController::class, 'processDeleteProductType'])->name('processDeleteProductType')->middleware('auth');

Route::get('/admin/studenten/{studentId}', [StudentController::class, 'showStudent'])->name('showStudent')->middleware('auth');
Route::get('/admin/docenten/{teacherId}', [TeacherController::class, 'showTeacher'])->name('showTeacher')->middleware('auth');


Route::get('/admin/import', [ImportController::class, 'listImports'])->name('import')->middleware('auth');
Route::post('/admin/import/product', [ImportController::class, 'processProductImport'])->name('processProductImport')->middleware('auth');
Route::post('/admin/import/product/overwrite', [ImportController::class, 'processProductImportOverwrite'])->name('processProductImportOverwrite')->middleware('auth');
Route::post('/admin/import/image', [ImportController::class, 'processImageImport'])->name('processImageImport')->middleware('auth');
Route::get('/admin/import/studenten', [ImportController::class, 'processStudentImport'])->name('processStudentImport')->middleware('auth');

Route::get('/admin/archief/producten', [ProductController::class, 'showArchivedProducts'])->name('showArchivedProducts')->middleware('auth');
Route::get('/admin/archief/producten/{productId}', [ProductController::class, 'showArchivedProduct'])->name('showArchivedProduct')->middleware('auth');
Route::get('/admin/archief/producten/{productId}/dearchiveren', [ProductController::class, 'processDearchiveProduct'])->name('processDearchiveProduct')->middleware('auth');

Route::get('/admin/register-device', [DeviceRegisterController::class, 'longRegisterDevice'])->name('longRegisterDevice')->middleware('auth');

// AJAX routes
Route::get('/find-products', [ProductController::class, 'findProducts'])->name('findProducts');
Route::get('/find-manage-products', [ProductController::class, 'findManageProducts'])->name('findManageProducts');
Route::get('/find-manage-product-types', [ProductTypeController::class, 'findManageProductTypes'])->name('findManageProductTypes');
Route::get('/find-reservations', [ReservationController::class, 'findReservations'])->name('findReservations');

// Auth routes

Route::get('/login', function(){return redirect('/amoclient/redirect');})->name('login');
Route::get('/amoclient/ready', [DeviceRegisterController::class, 'registerDevice']);

Route::get('/logout', function(){return redirect('/amoclient/logout');})->name('logout');

Route::get('/register', function() {return abort(404);});
Route::post('/register', function() {return abort(404);});

Route::get('/admin/new-user', [RegisterController::class, 'showRegistrationForm'])->name('newUser')->middleware('auth');
Route::post('/admin/new-user', [RegisterController::class, 'register'])->name('processNewUser')->middleware('auth');
