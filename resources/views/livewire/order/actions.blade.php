<div>
    @if ($settled)
        <x-badge lg rounded positive label="Liquidado"/>
        <x-button.circle title="Ver" sky icon="eye" wire:click="$emitTo('order.modal','openModal',{{ $id }})"/>
    @else
        <x-button.circle title="Editar" warning icon="pencil" wire:click="$emitTo('order.modal','openModal',{{ $id }})"/>
        <x-button.circle title="Liquidar" blue icon="cash" wire:click="$emitTo('settlement.modal','openModal',0,{{ $id }})"/>
        <x-button.circle title="Eliminar" negative icon="trash"
            x-on:confirm="{
                title: 'Seguro de Eliminar?',
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
