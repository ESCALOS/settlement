<?php

namespace App\Http\Livewire;

use App\Exceptions\ImportErrorException;
use App\Exports\FormatExport;
use App\Imports\OrdersImport;
use App\Imports\ProductImport;
use App\Imports\WarehouseInputImport;
use Illuminate\Support\Facades\Response;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ImportModal extends Component
{
    use WithFileUploads;
    use LivewireAlert;

    public $model;
    public $columns = [];
    public $openImport = false;
    public $archivo;
    public $fileNumber;

    protected $listeners = ['openImportModal'];

    public function rules(){
        return [
            'archivo' => 'required|file'
        ];
    }

    public function openImportModal():void{
        $this->fileNumber++;
        $this->archivo = null;
        $this->openImport = true;
    }

    public function import():void{
        $this->validate();
        try {
            switch ($this->model) {
                case 'Order':
                    $model = new OrdersImport;
                    break;

                default:
                    $this->alert('error','¡Error del sistema!',[
                        'position' => 'center',
                        'timer' => 2000,
                        'toast' => false
                    ]);
                    return;
            }
            Excel::import($model,$this->archivo);
            $this->emit('refreshDatatable');
            $this->alert('success','¡Importación exitosa!',[
                'position' => 'center',
                'timer' => 2000,
                'toast' => false
            ]);
            $this->openImport = false;
        }catch(\Maatwebsite\Excel\Validators\ValidationException $e){
            $errores = $e->failures();
            $this->alert('error', $errores[0]->errors(), [
                'position' => 'center',
                'timer' => 10000,
                'toast' => false,
            ]);
        }catch (ImportErrorException $e) {
            $this->alert('error', $e->getMessage(), [
                'position' => 'center',
                'timer' => 10000,
                'toast' => false,
            ]);
        } catch (\Exception $e) {
            $this->alert('error',$e->getMessage(), [
                'position' => 'center',
                'timer' => 10000,
                'toast' => false,
            ]);
        }
    }

    public function exportFormat(){
        $export = new FormatExport($this->columns);
        return Response::streamDownload(function () use ($export) {
            Excel::store($export, 'temp.xlsx');
            readfile(storage_path('app/temp.xlsx'));
        }, 'Formato.xlsx');
    }

    public function render()
    {
        return view('livewire.import-modal');
    }
}
