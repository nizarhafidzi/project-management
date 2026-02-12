<?php

namespace Modules\Operations\Livewire;

use Livewire\Component;

class BimViewer extends Component
{
    #[\Livewire\Attributes\Layout('layouts.app')]
    public function render()
    {
        return view('operations::livewire.bim-viewer');
    }
}
