<div>
    <x-header-crud action="$emitTo('order.modal','openModal',0)">Órdenes</x-header-crud>
    <div class="p-4">
        <livewire:order.order-table>
    </div>
    <livewire:order.modal>
    <livewire:settlement.modal>
</div>
