<div>
    <x-button.circle title="Ver" spinner="showDetails" sky icon="eye" wire:click="showDetails({{ $id }})"/>
    @if ($wmt == 0)
    <x-button.circle title="Editar" spinner="openModal" warning icon="pencil" wire:click="openModal({{ $id }})"/>
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
