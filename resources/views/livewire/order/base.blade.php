<div>
    <x-header-crud action="$emitTo('order.modal','openModal',0)" import="1">Órdenes</x-header-crud>
    <div class="p-4">
        <livewire:order.order-table>
    </div>
    <livewire:order.modal>
    <livewire:settlement.modal>
    <livewire:import-modal :model="$model" :columns="$columns">
</div>
