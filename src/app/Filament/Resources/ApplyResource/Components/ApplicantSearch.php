<?php

namespace App\Http\Livewire;

use App\Models\Apply;
use Filament\Forms;
use Filament\Resources\Form;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class ApplicantSearch extends Component implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;


    public function render(): View
    {
        return view('filament.resources.apply-resource.components.search');
    }
}
