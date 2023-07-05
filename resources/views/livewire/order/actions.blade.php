<div>
    @if ($settled)
        <x-badge rounded positive label="Liquidado" />
    @else
        <x-button.circle title="Editar" warning icon="pencil" wire:click="$emitTo('order.modal','openModal',{{ $id }})"/>
        <x-button.circle title="Liquidar" blue icon="cash" wire:click="$emitTo('settlement.modal','openModal',0,{{ $id }})"/>
        <x-button.circle title="Eliminar" negative icon="trash" wire:click="confirmDelete({{ $id }})"/>
    @endif
</div>
