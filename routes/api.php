<?php

use App\Http\Controllers\TransactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*
| -------------------------------------------------------------------------
| CONSTANTS description:
| -------------------------------------------------------------------------
| PATH_REGISTER:  /register
| PATH_SAVE: /store
| PATH_LIST_ALL: /list/all
| METHOD_STORE: store
| METHOD_ALL: all
| PATH_BASE: /
| PATH_NAME_STORE: store
| PATH_NAME_LIST_ALL: list.all
| PATH_NAME_INDEX: index
| PATH_NAME_REGISTER: register
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});