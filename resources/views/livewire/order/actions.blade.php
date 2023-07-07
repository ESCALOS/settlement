<div>
    @if ($settled)
        <div>
            <x-badge lg rounded positive label="Liquidado"/>
            <x-button.circle title="Ver" sky icon="eye" wire:click="$emitTo('order.modal','openModal',{{ $id }})"/>
        </div>
    @else
        <x-button.circle title="Editar" warning icon="pencil" wire:click="$emitTo('order.modal','openModal',{{ $id }})"/>
        <x-button.circle title="Liquidar" blue icon="cash" wire:click="$emitTo('settlement.modal','openModal',0,{{ $id }})"/>
        <x-button.circle title="Eliminar" negative icon="trash" wire:click="confirmDelete({{ $id }})"/>
    @endif
</div>
