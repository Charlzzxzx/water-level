<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\WaterLevel;
use Illuminate\Support\Facades\DB;

class GraphDefault extends Component
{
    public $waterLevels;

    public $location;

    public function render()
    {
        $view = 'livewire.components.graph-default';
        return view($view, ['location' => $this->location]);
    }

    public function chartData()
    {

        $this->waterLevels = DB::table('vwwaterlevels')->where('location', '=', $this->location)
            ->latest('timestamp')
            ->limit(10)
            ->get();
       
    }

    public function mount($location)
    {
        $this->location = $location;
        $this->chartData();
    }
}
