<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

// REGISTER
Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// LOGIN
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');

// LOGOUT
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| HOME / DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');


/*
|--------------------------------------------------------------------------
| HOME / BOOKING
|--------------------------------------------------------------------------
*/

Route::get('/booking/{lapangan}', [BookingController::class, 'create'])
    ->middleware('auth')
    ->name('booking.create');

Route::post('/booking', [BookingController::class, 'store'])
    ->middleware('auth')
    ->name('booking.store');





/*
|--------------------------------------------------------------------------
| TRANSACTION
|--------------------------------------------------------------------------
*/

Route::post('/transaction/preview', [TransactionController::class, 'preview'])
    ->middleware('auth')
    ->name('transaction.preview');

Route::post('/transaction/store', [TransactionController::class, 'store'])
    ->middleware('auth')
    ->name('transaction.store');

Route::get('/transaction/{transaction}', [TransactionController::class, 'show'])
    ->middleware('auth')
    ->name('transaction.show');

Route::get('/riwayat-transaksi', [TransactionController::class, 'index'])
    ->middleware('auth')
    ->name('riwayat.transaksi');


use App\Http\Controllers\JadwalController;

Route::get('/jadwal-lapangan', [JadwalController::class, 'index'])
    ->name('jadwal.lapangan');

Route::post('/cek-ketersediaan', [JadwalController::class, 'cekKetersediaan'])
    ->name('jadwal.cek');



/*
|--------------------------------------------------------------------------
| HOME / RIWAYAT PESANAN
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/riwayat-pesanan', [BookingController::class, 'riwayat'])
        ->name('riwayat.pesanan');

    Route::get('/riwayat-pesanan/{booking}', [BookingController::class, 'detail'])
        ->name('riwayat.pesanan.detail');
});


/*
|--------------------------------------------------------------------------
| HOME / RIWAYAT TRANSAKSI
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/riwayat-transaksi', [TransactionController::class, 'index'])
        ->name('riwayat.transaksi');
});



/*
|--------------------------------------------------------------------------
| HOME / PROFIL
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profil', [ProfileController::class, 'index'])->name('profil');
    Route::post('/profil/update', [ProfileController::class, 'update'])->name('profil.update');
    Route::post('/profil/password', [ProfileController::class, 'password'])->name('profil.password');
});




/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\LapanganController;
use App\Http\Controllers\Admin\AdminJadwalController;
use App\Http\Controllers\Admin\AdminBookingController;
use App\Http\Controllers\Admin\AdminTransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\LaporanExportController;

Route::prefix('admin')
    ->middleware(['auth', 'admin'])
    ->name('admin.')
    ->group(function () {

        // DASHBOARD
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // LAPANGAN
        Route::get('/lapangan', [LapanganController::class, 'index'])
            ->name('lapangan.index');
        Route::get('/lapangan/create', [LapanganController::class, 'create'])
            ->name('lapangan.create');
         Route::post('/lapangan', [LapanganController::class, 'store'])
            ->name('lapangan.store');
        Route::get('/lapangan/{lapangan}/edit', [LapanganController::class, 'edit'])
            ->name('lapangan.edit');
        Route::put('/lapangan/{lapangan}', [LapanganController::class, 'update'])
            ->name('lapangan.update');
        Route::delete('/lapangan/{lapangan}', [LapanganController::class, 'destroy'])
            ->name('lapangan.destroy');
        
        // JADWAL
        Route::get('/jadwal', [AdminJadwalController::class, 'index'])
            ->name('jadwal.index');
        Route::post('/jadwal', [AdminJadwalController::class, 'update'])
            ->name('jadwal.update');

        // BOOKING
        Route::get('/booking', [AdminBookingController::class, 'index'])
            ->name('booking.index');

        Route::get('/booking/{booking}', [AdminBookingController::class, 'show'])
            ->name('booking.show');

        Route::post('/booking/{booking}/status', [AdminBookingController::class, 'updateStatus'])
            ->name('booking.updateStatus');

        // TRANSACTION
        Route::get('/transaction', [AdminTransactionController::class, 'index'])
            ->name('transaction.index');

        Route::post('/transaction/{transaction}/konfirmasi', [AdminTransactionController::class, 'konfirmasi'])
            ->name('transaction.konfirmasi');

        Route::post('/transaction/{transaction}/batalkan', [AdminTransactionController::class, 'batalkan'])
            ->name('transaction.batalkan');

        // PENGGUNA
        Route::resource('users', UserController::class);

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        // LAPORAN
        Route::get('/laporan', [LaporanController::class, 'index'])
        ->name('laporan.index');

        Route::get('/laporan/pdf', [LaporanExportController::class, 'exportPdf'])
            ->name('laporan.pdf');

        Route::get('/laporan/excel', [LaporanExportController::class, 'exportExcel'])
            ->name('laporan.excel');

        Route::get('/laporan/csv', [LaporanExportController::class, 'exportCsv'])
            ->name('laporan.csv');

        Route::get('/laporan/word', [LaporanExportController::class, 'exportWord'])
            ->name('laporan.word');

});
