<?php

namespace App\View\Components\Form;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Input extends Component
{
    public function render(): View
    {
        return view('components.form.input');
    }
}
