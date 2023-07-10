<div>
    @if ($shipped)
    <x-button.circle title="Ver" spinner="openPreview" sky icon="eye" wire:click="openPreview({{ $id }})"/>
    <x-button.circle title="Anular Envío" negative icon="arrow-left"
    x-on:confirm="{
        title: '¿Seguro de anular el envío?',
        icon: 'warning',
        accept: {
            label: 'Sí',
            method: 'unship',
            params: '{{ $id }}'
        },
        reject: {
            label: 'No',
        }
    }"/>
    @else
    <x-button.circle title="Editar" spinner="openModal" warning icon="pencil" wire:click="openModal({{ $id }})"/>
    <x-button.circle title="Eliminar" negative icon="trash"
        x-on:confirm="{
            title: '¿Seguro de deshacer la mezcla?',
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
    <x-button.circle title="Enviar" positive icon="arrow-right"
        x-on:confirm="{
            title: '¿Seguro de enviar?',
            icon: 'warning',
            accept: {
                label: 'Sí',
                method: 'ship',
                params: '{{ $id }}'
            },
            reject: {
                label: 'No',
            }
        }"/>
    @endif
</div>
