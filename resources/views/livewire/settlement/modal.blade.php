<x-modal.card title="Liquidación de Órden" blur fullscreen wire:model.defer="open">
    <div class="grid grid-cols-5 gap-4 mb-4">
        <x-datetime-picker
            label="Fecha"
            wire:model.defer="date"
            without-time=false
        />
        <x-native-select
            label="Factura"
            :options="[
                ['name' => 'Sí',  'id' => 1],
                ['name' => 'No', 'id' => 0],
            ]"
            wire:model.defer='invoice'
            option-label="name"
            option-value="id"
        />
        <x-input type="number" label="Cobre Internacional" wire:model.defer='internationalPayment.copper'/>
        <x-input type="number" label="Plata Internacional" wire:model.defer='internationalPayment.silver'/>
        <x-input type="number" label="Oro Internacional" wire:model.defer='internationalPayment.gold'/>
    </div>
    <div class="grid grid-cols-3 gap-4 my-4">
        <div class="col-span-3 p-2 text-lg text-center text-white bg-blue-900 rounded-md">
            Leyes
        </div>
        <x-input type="number" label="Ley Cobre(%):" wire:model.defer='law.copper'/>
        <x-input type="number" label="Humedad H2O(%):" wire:model.defer='law.humidity'/>
        <x-input type="number" label="Merma(%):" wire:model.defer='law.decrease'/>
    </div>
    <div class="grid grid-cols-4 gap-4 mb-4">
        <x-input type="number" label="Ley Plata(Oz):" wire:model.defer='law.silver'/>
        <x-input type="number" label="Factor:" wire:model.defer='law.silverFactor'/>
        <x-input type="number" label="Ley Oro(Oz):" wire:model.defer='law.gold'/>
        <x-input type="number" label="Factor:" wire:model.defer='law.goldFactor'/>
    </div>
    <div class="grid grid-cols-3 gap-4 my-4">
        <div class="col-span-3 p-2 text-lg text-center text-white bg-green-500 rounded-md">
            Porcentajes Pagables
        </div>
        <x-input type="number" label="Cobre(%)" wire:model.defer='percentagePayable.copper'/>
        <x-input type="number" label="Plata(%)" wire:model.defer='percentagePayable.silver'/>
        <x-input type="number" label="Oro(%)" wire:model.defer='percentagePayable.gold'/>
    </div>
    <div class="grid grid-cols-3 gap-4 my-4">
        <div class="col-span-3 p-2 text-lg text-center text-white bg-orange-500 rounded-md">
            Requerimientos
        </div>
        <x-input type="number" label="Protección Cobre" wire:model.defer='requirement.protection.copper'/>
        <x-input type="number" label="Protección Plata" wire:model.defer='requirement.protection.silver'/>
        <x-input type="number" label="Protección Oro" wire:model.defer='requirement.protection.gold'/>
        <x-input type="number" label="Deducción Cobre" wire:model.defer='requirement.deduction.copper'/>
        <x-input type="number" label="Deducción Plata" wire:model.defer='requirement.deduction.silver'/>
        <x-input type="number" label="Deducción Oro" wire:model.defer='requirement.deduction.gold'/>
        <x-input type="number" label="Refinamiento Cobre" wire:model.defer='requirement.refinement.copper'/>
        <x-input type="number" label="Refinamiento Plata" wire:model.defer='requirement.refinement.silver'/>
        <x-input type="number" label="Refinamiento Oro" wire:model.defer='requirement.refinement.gold'/>
        <x-input type="number" label="Maquila" wire:model.defer='requirement.other.maquila'/>
        <x-input type="number" label="Análisis" wire:model.defer='requirement.other.analysis'/>
        <x-input type="number" label="Estibadores" wire:model.defer='requirement.other.stevedore'/>
    </div>
    <div class="grid grid-cols-6 gap-4 my-4">
        <div class="col-span-6 p-2 text-lg text-center text-white bg-red-500 rounded-md">
            Penalidades
        </div>
        <x-input type="number" label="Arsénico" wire:model.defer='penalty.arsenic'/>
        <x-input type="number" label="Antimonio" wire:model.defer='penalty.antomony'/>
        <x-input type="number" label="Plomo" wire:model.defer='penalty.lead'/>
        <x-input type="number" label="Zinc" wire:model.defer='penalty.zinc'/>
        <x-input type="number" label="Bismuto" wire:model.defer='penalty.bismuth'/>
        <x-input type="number" label="Mercurio" wire:model.defer='penalty.mercury'/>
    </div>
    <div class="grid grid-cols-6 gap-4 my-4">
        <div class="col-span-6 p-2 text-lg text-center text-white bg-purple-500 rounded-md">
            Máximo Permitido
        </div>
        <x-input type="number" label="Arsénico" wire:model.defer='maximum.arsenic'/>
        <x-input type="number" label="Antimonio" wire:model.defer='maximum.antomony'/>
        <x-input type="number" label="Plomo" wire:model.defer='maximum.lead'/>
        <x-input type="number" label="Zinc" wire:model.defer='maximum.zinc'/>
        <x-input type="number" label="Bismuto" wire:model.defer='maximum.bismuth'/>
        <x-input type="number" label="Mercurio" wire:model.defer='maximum.mercury'/>
    </div>
    <div class="grid grid-cols-6 gap-4 my-4">
        <div class="col-span-6 p-2 text-lg text-center text-white bg-yellow-500 rounded-md">
            Precio por Penalidades
        </div>
        <x-input type="number" label="Arsénico" wire:model.defer='price.arsenic'/>
        <x-input type="number" label="Antimonio" wire:model.defer='price.antomony'/>
        <x-input type="number" label="Plomo" wire:model.defer='price.lead'/>
        <x-input type="number" label="Zinc" wire:model.defer='price.zinc'/>
        <x-input type="number" label="Bismuto" wire:model.defer='price.bismuth'/>
        <x-input type="number" label="Mercurio" wire:model.defer='price.mercury'/>
    </div>
    <x-slot name="footer">
        <div class="flex justify-end gap-x-4">
            <div class="flex">
                <x-button flat label="{{ __('Cancel') }}" x-on:click="close" />
                <x-button primary spinner label="{{ __('Save') }}"
                    x-on:confirm="{
                        title: '¿Seguro de Liquidar?',
                        icon: 'info',
                        accept: {
                            label: 'Sí',
                            method: 'save',
                        },
                        reject: {
                            label: 'No',
                        }
                    }"/>
            </div>
        </div>
    </x-slot>
</x-modal.card>
