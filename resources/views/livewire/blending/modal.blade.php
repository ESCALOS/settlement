<x-modal.card title="Mezclar liquidaciones" blur max-width="4xl" wire:model.defer="open">
    <div class="grid grid-cols-2 gap-4 mb-2">
        <x-datetime-picker
            label="Fecha"
            wire:model.defer="date"
            without-time=false
        />
        <x-input label="TMH Total" readonly wire:model='wmtTotal'/>
    </div>
    @foreach ($settlements as $index => $settlement)
        <div class="grid grid-cols-5 gap-4 mb-2">
            <x-input label="Lote" readonly wire:model.defer='settlements.{{ $index }}.batch'/>
            <x-input label="Concentrado" readonly wire:model.defer='settlements.{{ $index }}.concentrate'/>
            <x-input label="TMH total" readonly wire:model.defer='settlements.{{ $index }}.wmt'/>
            <x-input label="TMH faltante" readonly wire:model.defer='settlements.{{ $index }}.wmt_missing'/>
            <x-input type="number" label="TMH a mezclar" wire:model.debounce.250ms='settlements.{{ $index }}.wmt_to_blending'/>
        </div>
    @endforeach
    <x-slot name="footer">
        <div class="flex justify-between gap-x-4">
            <div>
                <x-button info spinner="preview" icon="eye" wire:click="preview"/>
            </div>
            <div class="flex">
                <x-button flat label="{{ __('Close') }}" x-on:click="close" />
                <x-button primary spinner label="{{ __('Save') }}"
                x-on:confirm="{
                    title: '¿Seguro de mezclar?',
                    icon: 'warning',
                    accept: {
                        label: 'Sí',
                        method: 'blending',
                    },
                    reject: {
                        label: 'No',
                    }
                }"/>
            </div>
        </div>
    </x-slot>
</x-modal.card>
