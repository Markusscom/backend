<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PIMController;

#Route::get('/user', function (Request $request) {
#    return $request->user();
#})->middleware('auth:sanctum');

Route::post('/admin-login', [AdminController::class, "login"]);

Route::get('/websites', [PIMController::class, 'websites']);
Route::post('/websites', [PIMController::class, 'createWebsite']);
Route::put('/websites/{id}', [PIMController::class, 'updateWebsite']);
Route::delete('/websites/{id}', [PIMController::class, 'deleteWebsite']);

Route::get('/websites/{website_id}/products', [PIMController::class, 'products']);
Route::post('/websites/{website_id}/products', [PIMController::class, 'createProduct']);
Route::put('/products/{id}', [PIMController::class, 'updateProduct']);
Route::delete('/products/{id}', [PIMController::class, 'deleteProduct']);
