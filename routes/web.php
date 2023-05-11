<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Utils\CpfUtil;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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


Route::get('/', function () {
    return Inertia::render('Auth/Login', [
        'canResetPassword' => Route::has('password.request'),
        'status' => session('status'),
    ]);
});

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('transactions')->name('transactions.')
    ->middleware(['auth', 'verified', 'user.common'])    
    ->group(function () {
        Route::get(config('constants.route.PATH_BASE'), fn() =>
            Inertia::render('Transaction/Index'))
                ->name(config('constants.route.PATH_NAME_INDEX'));
        Route::get(config('constants.route.PATH_REGISTER'), fn() =>
            Inertia::render('Transaction/Register'))
                ->name(config('constants.route.PATH_NAME_REGISTER'));
});

Route::controller(TransactionController::class)
    ->prefix('transactions')
    ->name('transactions.')
    ->middleware(['auth', 'verified', 'user.common'])
    ->group(function () {
        Route::post(config('constants.route.PATH_SAVE'), config('constants.route.METHOD_STORE'))
            ->name(config('constants.route.PATH_NAME_STORE'));
        Route::get(config('constants.route.PATH_LIST_ALL'), config('constants.route.METHOD_ALL'))
            ->name(config('constants.route.PATH_NAME_LIST_ALL'));
        Route::get(config('constants.route.PATH_LIST_ALL').'/by/user/from',
            config('constants.route.METHOD_ALL').'ByUserFrom')
            ->name(config('constants.route.PATH_NAME_LIST_ALL').'.by.user.from');
});

Route::post('/teste', function(Request $req) {
    return CpfUtil::cpfValidation($req->cpf_cnpj) ? 'sim' : 'nao'; 
})->name('teste');

require __DIR__.'/auth.php';
