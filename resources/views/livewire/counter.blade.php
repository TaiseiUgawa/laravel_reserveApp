<div style="text-align: center">
    <button wire:click="increment">+</button>
    <h1>{{ $count }}</h1>
    <div class="mb-8"></div>

    こんにちは、{{ $name }}さん<br>
    {{-- <input type="text" wire:model="name">
    <input type="text" wire:model.debounce.2000ms="name">
    <input type="text" wire:model.defer="name"> --}}
    <input type="text" wire:model.lazy="name"><br>

    {{-- action --}}
    <button wire:mouseover='mouseOver'>マウスを合わせてください</button>
</div>
