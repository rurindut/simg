<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CetakAnggotaController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::redirect('/', '/admin/login');

Route::get('/admin/anggota/{record}/cetak', [CetakAnggotaController::class, 'cetak'])->name('anggota.cetak');
