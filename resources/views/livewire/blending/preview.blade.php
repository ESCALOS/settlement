<x-modal.card title="Vista Previa de Mezcla" blur max-width="6xl" wire:model.defer="open">
    @foreach ($settlements as $index => $settlement)
        <div class="grid grid-cols-7 gap-4 px-4 pb-4">
            <x-input label="Lote" readonly  value="{{ $settlement['batch'] }}"/>
            <x-input label="Concentrado" readonly  value="{{ $settlement['concentrate'] }}"/>
            <x-input label="TMH" readonly  value="{{ $settlement['wmt_to_blending'] }}"/>
            <x-input label="TMNS" readonly  value="{{ $settlement['dnwmt'] }}"/>
            <x-input label="Subtotal" readonly value="$ {{ number_format($settlement['amount'] - $settlement['igv'],2) }}"/>
            <x-input label="IGV" readonly value="$ {{ $settlement['igv'] }}"/>
            <x-input label="Total" readonly value="$ {{ number_format($settlement['amount'],2) }}"/>
        </div>
    @endforeach
    <hr>
    <div class="w-full py-2 mt-4 rounded-lg bg-amber-600">
        <h1 class="text-xl font-bold text-center text-white">Promedio de Leyes</h1>
    </div>
    <div class="grid grid-cols-3 gap-4 p-4">
        <x-input label="Cobre" readonly  value="{{ $law['copper'] }}"/>
        <x-input label="Plata" readonly  value="{{ $law['silver'] }}"/>
        <x-input label="Oro" readonly  value="{{ $law['gold'] }}"/>
    </div>
    <div class="w-full py-2 mt-4 bg-red-600 rounded-lg">
        <h1 class="text-xl font-bold text-center text-white">Promedio de Penalidades</h1>
    </div>
    <div class="grid grid-cols-6 gap-4 p-4">
        <x-input label="Arsénico" readonly  value="{{ $penalty['arsenic'] }}"/>
        <x-input label="Antomonio" readonly  value="{{ $penalty['antomony'] }}"/>
        <x-input label="Plomo" readonly  value="{{ $penalty['lead'] }}"/>
        <x-input label="Zinc" readonly  value="{{ $penalty['zinc'] }}"/>
        <x-input label="Bismuto" readonly  value="{{ $penalty['bismuth'] }}"/>
        <x-input label="Mercurio" readonly  value="{{ $penalty['mercury'] }}"/>
    </div>
    <div class="w-full py-2 mt-4 bg-green-600 rounded-lg">
        <h1 class="text-xl font-bold text-center text-white">Total</h1>
    </div>
    <div class="grid grid-cols-3 gap-4 p-4">
        <x-input label="TMH" readonly  value="{{ $total['wmt'] }}"/>
        <x-input label="TMNS" readonly  value="{{ $total['dnwmt'] }}"/>
        <x-input label="Monto" readonly  value="$ {{ number_format($total['amount'],2) }}"/>
    </div>
    <x-slot name="footer">
        <div class="flex justify-end gap-x-4">
            <div class="flex">
                <x-button flat label="{{ __('Close') }}" x-on:click="close" />
            </div>
        </div>
    </x-slot>
</x-modal.card>
