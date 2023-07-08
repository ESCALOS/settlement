<div>
    @if ($settled)
        <x-badge lg rounded positive label="Liquidado"/>
        <x-button.circle title="Ver" spinner="openModal" sky icon="eye" wire:click="openModal({{ $id }})"/>
    @else
        <x-button.circle title="Editar" spinner="openModal" warning icon="pencil" wire:click="openModal({{ $id }})"/>
        <x-button.circle title="Liquidar" blue icon="cash" spinner="openSettlement" wire:click="openSettlement({{ $id }})"/>
        <x-button.circle title="Eliminar" negative icon="trash"
            x-on:confirm="{
                title: '¿Seguro de Eliminar?',
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
