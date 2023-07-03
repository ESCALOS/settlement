<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Concentrate;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class ConcentrateController extends Controller
{
    public function __invoke(Request $request): Collection
    {
        return Concentrate::query()
            ->select('id','concentrate')
            ->orderBy('concentrate')
            ->when(
                $request->search,
                fn (Builder $query) => $query
                    ->where('concentrate','like',"%{$request->search}%")
            )
            ->when(
                $request->exists('selected'),
                fn (Builder $query) => $query->whereIn('id', $request->input('selected', [])),
                fn (Builder $query) => $query->limit(10)
            )
            ->get()
            ->map(function (Concentrate $concentrate) {
                return $concentrate;
            });
    }
}
