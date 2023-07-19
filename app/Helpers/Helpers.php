<?php

namespace App\Helpers;

use App\Models\DispatchDetail;
use App\Models\Entity;
use App\Models\Settlement;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Helpers
{
    use LivewireAlert;

    /**
     * Verifica si el numero es un numero de RUC o DNI
     * @param string El numero que se quiere validar
     * @return string Devuelve dni o ruc en caso lo fuesen, sino devuelve el numero de digitos que tiene
     */
    public static function checkDocumentNumber($documentNumber):string{
        switch (strlen($documentNumber)) {
            case 8:
                return "dni";
            case 11:
                return "ruc";
            default:
                return "";
        }
    }

    /**
     * Buscar en el api de sunat y lo guarda en la base de datos
     * @param int Numero del documento
     * @param string Tipo de documento en minuscula: ruc o dni
     * @return ?Entity Regresa una instancia de la entidad o null sino lo encuentra
     */
    public static function getEntityApi(int $documentNumber, string $documentType): ?Entity
    {
        try {
            // Iniciar llamada a API
            $curl = curl_init();

            // Buscar ruc sunat
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.apis.net.pe/v1/' . $documentType . '?numero=' . $documentNumber,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Referer: http://apis.net.pe/api-ruc',
                    'Authorization: Bearer ' . env('API_SUNAT')
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $company = json_decode($response);
            if (isset($company->numeroDocumento)) {
                $entity = Entity::create([
                    'document_number' => $company->numeroDocumento,
                    'name' => strtoupper($company->nombre),
                    'address' => strtoupper($company->direccion)
                ]);
                return $entity;
            } else {
                return null;
            }
        } catch (\Throwable $th) {
            return null;
        }

    }

    /**
     * Buscar por dni o ruc
     * @param string $documentNumber Número de DNI/RUC
     * @return Entity Retorna un objeto del modelo entity o nulo si no lo encuentra
     * @Nanoka
     */
    public function searchRuc(string $documentNumber):?Entity{
        $documentType = $this->checkDocumentNumber($documentNumber);
        if($documentType == ""){
            return null;
        }

        //Buscar en la Base de Datos
        if(Entity::where('document_number',$documentNumber)->exists()){
            $entity = Entity::where('document_number',$documentNumber)->first();
        }else{
            $entity = $this->getEntityApi($documentNumber,$documentType);
        }
        return $entity;
    }

    /**
     * @param string $table Nombre de la tabla
     * @param string $preffix Prefijo del correlativo
     * @return string El correlativo del lote
     */
    public static function createBatch(string $table,string $preffix):string{
        $date = $preffix.''.Carbon::now()->isoFormat('YYMM');
        $correlative = '0001';
        if(DB::table($table)->limit(1)->exists()){
            $last_batch = explode("-",DB::table($table)->orderBy('batch','desc')->first()->batch);
            if($date == $last_batch[0]){
                $correlative = str_pad(strval(intval($last_batch[1])+1),4,0,STR_PAD_LEFT);
            }
        }
        return $date.'-'.$correlative;
    }

    /**
     * @param array[] Array de todas las liquidaciones a mezclar en donde debe tener cada liquidacion las llaves:
     *               - id
     *               - wmt
     *               - wmt_to_blending
     * @return array[] Retorna un array que contiene los siguientes arrays:
     *               - Settlements: Lidaciones del parametro más los siguientes campos: factor, dnwmt, igv y amount.
     *               - Law: copper, silver y gold.
     *               - Penalty: arsenic, antomoy, lead, zinc, bismuth y mercury.
     *               - Total: wmt, dnwmt y amount
     */
    public static function getBlendingData(array $settlements): array{
        $law = [
            'copper' => 0,
            'silver' => 0,
            'gold' => 0,
        ];
        $penalty = [
            'arsenic' => 0,
            'antomony' => 0,
            'lead' => 0,
            'zinc' => 0,
            'bismuth' => 0,
            'mercury' => 0,
        ];
        $total = [
            'wmt' => 0,
            'dnwmt' => 0,
            'amount' => 0,
        ];
        foreach($settlements as $key => $settlement){
            $model = Settlement::find($settlement['id']);
            $fraccion = $settlements[$key]['wmt_to_blending']/$settlements[$key]['wmt'];
            $settlements[$key]['factor'] = $model->Law->tmns/$model->Order->wmt;
            $settlements[$key]['dnwmt'] = round($settlements[$key]['wmt_to_blending']*$settlements[$key]['factor'],3);
            $settlements[$key]['igv'] = round($model->SettlementTotal->igv*$fraccion,2);
            $settlements[$key]['amount'] = round(($model->SettlementTotal->batch_price+$model->SettlementTotal->igv)*$fraccion,2);
            $law['copper'] += $settlements[$key]['dnwmt']*$model->Law->copper;
            $law['silver'] += $settlements[$key]['dnwmt']*$model->Law->silver;
            $law['gold'] += $settlements[$key]['dnwmt']*$model->Law->gold;
            $penalty['arsenic'] += $settlements[$key]['dnwmt']*$model->Penalty->arsenic;
            $penalty['antomony'] += $settlements[$key]['dnwmt']*$model->Penalty->antomony;
            $penalty['lead'] += $settlements[$key]['dnwmt']*$model->Penalty->lead;
            $penalty['zinc'] += $settlements[$key]['dnwmt']*$model->Penalty->zinc;
            $penalty['bismuth'] += $settlements[$key]['dnwmt']*$model->Penalty->bismuth;
            $penalty['mercury'] += $settlements[$key]['dnwmt']*$model->Penalty->mercury;
            $total['wmt'] += $settlements[$key]['wmt_to_blending'];
            $total['dnwmt'] += $settlements[$key]['dnwmt'];
            $total['amount'] += $settlements[$key]['amount'];
        }
        $law['copper'] = number_format($law['copper']/$total['dnwmt'],3);
        $law['silver'] = number_format($law['silver']/$total['dnwmt'],3);
        $law['gold'] = number_format($law['gold']/$total['dnwmt'],3);
        $penalty['arsenic'] = number_format($penalty['arsenic']/$total['dnwmt'],3);
        $penalty['antomony'] = number_format($penalty['antomony']/$total['dnwmt'],3);
        $penalty['lead'] = number_format($penalty['lead']/$total['dnwmt'],3);
        $penalty['zinc'] = number_format($penalty['zinc']/$total['dnwmt'],3);
        $penalty['bismuth'] = number_format($penalty['bismuth']/$total['dnwmt'],3);
        $penalty['mercury'] = number_format($penalty['mercury']/$total['dnwmt'],3);

        return [$settlements,$law,$penalty,$total];
    }

    /**
     * Recibe el id del despacho y retorna los datos del detalle de las liquidaciones
     * @param int $id El id del despacho
     * @return array Retorna el detalle de las liquidaciones
     */
    public static function getDispatchDetails($id):array{
        $settlements = [];
        $dispatchDetails = DispatchDetail::where('dispatch_id', $id)->get();
        foreach($dispatchDetails as $key => $dispatchDetail){
            $blended = DispatchDetail::where('settlement_id',$dispatchDetail->Settlement->id)->where('dispatch_id','<>',$id)->sum('wmt');
            $settlements[$key]['id'] = $dispatchDetail->settlement_id;
            $settlements[$key]['batch'] = $dispatchDetail->Settlement->batch;
            $settlements[$key]['concentrate'] = $dispatchDetail->Settlement->Order->Concentrate->concentrate;
            $settlements[$key]['wmt'] = number_format($dispatchDetail->Settlement->Order->wmt,3);
            $settlements[$key]['wmt_missing'] = number_format($dispatchDetail->Settlement->Order->wmt - $blended,3);
            $settlements[$key]['wmt_to_blending'] = $dispatchDetail->wmt;
        }
        return $settlements;
    }
}
