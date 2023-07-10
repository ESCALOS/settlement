<?php

use App\Http\Livewire\Blending\Base as Blending;
use App\Http\Livewire\Dispatch\Base as Dispatch;
use App\Http\Livewire\Order\Base as Order;
use App\Http\Livewire\Sent\Base as Sent;
use App\Http\Livewire\Settlement\Base as Settlement;
use Illuminate\Support\Facades\Route;

Route::get('/ordenes', Order::class)->name('admin.orders');
Route::get('/liquidaciones', Settlement::class)->name('admin.settlements');
Route::get('/blendings', Blending::class)->name('admin.blendings');
Route::get('/dispatches', Dispatch::class)->name('admin.dispatches');
Route::get('/sents', Sent::class)->name('admin.sents');
