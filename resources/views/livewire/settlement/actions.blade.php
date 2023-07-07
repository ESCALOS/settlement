<div>
    <x-button.circle title="Ver" sky icon="eye" wire:click="$emitTo('settlement.modal-detail','openModal',{{ $id }},0)"/>
    @if ($wmt == 0)
    <x-button.circle title="Editar" warning icon="pencil" wire:click="$emitTo('settlement.modal','openModal',{{ $id }},0)"/>
    <x-button.circle title="Eliminar" negative icon="trash"
        x-on:confirm="{
            title: '¿Seguro de eliminar?',
            icon: 'error',
            accept: {
                label: 'Sí',
                method: 'delete',
                params: '{{ $id }}'
            },
            reject: {
                label: 'No',
            }
        }"/>
    @endif
</div>
