<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $count = 0;

    public $name = '';

    // ライフサイクルフック
    // render()走る前に
    public function mount()
    {
        $this->name = 'mount';
    }

    // 更新時に走る
    public function updated()
    {
        $this->name = 'updated';
    }

    // action
    public function mouseOver()
    {
        $this->name = 'mouseover';
    }

    public function increment()
    {
        $this->count++;
    }

    public function render()
    {
        return view('livewire.counter');
    }
}
