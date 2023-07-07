<?php

use App\Http\Livewire\Order\Base as Order;
use App\Http\Livewire\Settlement\Base as Settlement;
use Illuminate\Support\Facades\Route;

Route::get('/ordenes',Order::class)->name('admin.orders');
Route::get('/liquidaciones',Settlement::class)->name('admin.settlements');
