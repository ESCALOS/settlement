<?php

namespace App\Imports;

use App\Exceptions\ImportErrorException;
use App\Helpers\Helpers;
use App\Models\Concentrate;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Row;

class OrdersImport implements OnEachRow,WithHeadingRow
{
    private $latestBatchNumber;

    public function __construct()
    {
        // Obtener el número de lote más alto actual
        $latestBatch = DB::table('orders')->orderBy('batch', 'desc')->first();
        $this->latestBatchNumber = $latestBatch ? substr($latestBatch->batch, -4) : 0;
    }

    public function onRow(Row $row)
    {
        DB::transaction(function () use ($row){
            $this->latestBatchNumber++;
            $helpers = new Helpers();

            $order = new Order();
            $order->batch = "O".date('ym')."-".str_pad($this->latestBatchNumber,4,'0',STR_PAD_LEFT);
            $fecha = $row['fecha'];
            $order->date = date('Y-m-d',strtotime("+$fecha days",strtotime('1899-12-30')));
            $order->ticket = $row['ticket'];
            $order->client_id = $helpers->searchRuc($row['ruc_cliente'])->id;
            $order->concentrate_id = Concentrate::firstOrCreate(['concentrate' => $row['concentrado']],['chemical_symbol' => $row['simbolo']])->id;
            $order->wmt = $row['tmh'];
            $order->origin = $row['procedencia'];
            $order->carriage_company_id = $helpers->searchRuc($row['ruc_transportista'])->id;
            $order->plate_number = $row['numero_de_placa'];
            $order->transport_guide = empty($row['guia_de_transporte']) ? '' : $row['guia_de_transporte'];
            $order->delivery_note = empty($row['guia_de_remision']) ? '' : $row['guia_de_remision'];
            $order->weighing_scale_company_id = $helpers->searchRuc($row['ruc_balanza'])->id;
            $order->user_id = Auth::user()->id;
            $order->save();
        });

    }
}
