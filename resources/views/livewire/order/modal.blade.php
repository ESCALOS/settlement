<x-modal.card title="{{ $orderId ? 'Editar' : 'Registrar' }} Órden" blur wire:model.defer="open">
    <div class="grid grid-cols-3 gap-4 mb-2">
        <x-datetime-picker
            label="Fechaa"
            wire:model.defer="date"
            without-time=false
        />
        <x-input label="Ticket" wire:model='ticket'/>
        <x-input type="number" label="DNI/RUC del Cliente" wire:model='client.documentNumber'/>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-2">
        <x-input label="Nombre del Cliente" wire:model='client.name'/>
        <x-input label="Dirección del Cliente" wire:model='client.address'/>
    </div>
    <div class="grid grid-cols-3 gap-4 mb-2">
        <x-select
            label="Concentrado"
            placeholder="Seleccione un concentrado"
            :async-data="route('api.concentrate')"
            option-label="concentrate"
            option-value="id"
        />
        <x-input type="number" label="TMH" wire:model='wmt'/>
        <x-input label="Procedencia" wire:model='origin'/>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-2">
        <x-input type="number" label="RUC del transportista" wire:model='carriage.documentNumber'/>
        <x-input label="Nombre del Transportista" wire:model='carriage.name'/>
    </div>
    <div class="grid grid-cols-3 gap-4 mb-2">
        <x-input label="N° de placa" wire:model='plateNumber'/>
        <x-input label="Guía de Transporte" wire:model='transportGuide'/>
        <x-input label="Guía de Remisión" wire:model='deliveryNote'/>
    </div>
    <div class="grid grid-cols-2 gap-4 mb-2">
        <x-input type="number" label="Ruc de la Balanza" wire:model='weighing.documentNumber'/>
        <x-input label="Nombre de la Balanza" wire:model='weighing.name'/>
    </div>
    <x-slot name="footer">
        <div class="flex justify-{{$orderId ? 'between' : 'end'}} gap-x-4">
            @if($orderId)
            <x-button flat negative label="{{ __('Delete') }}" wire:click="delete" />
            @endif
            <div class="flex">
                <x-button flat label="{{ __('Cancel') }}" x-on:click="close" />
                <x-button primary spinner label="{{ __('Save') }}" wire:click="save"/>
            </div>
        </div>
    </x-slot>
</x-modal.card>
