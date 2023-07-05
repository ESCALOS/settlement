<?php

namespace App\Helpers;
use App\Models\Entity;
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
    }

    public function searchRuc($documentNumber):?Entity{
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
}