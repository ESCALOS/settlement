<?php

use App\Http\Livewire\Order\Base as Order;
use Illuminate\Support\Facades\Route;

Route::get('/ordenes',Order::class)->name('admin.orders');
