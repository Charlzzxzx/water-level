<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\WaterLevel;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class TableDefault extends Component
{
    use WithPagination;

    public $place;

    public function mount($place)
    {
        $this->place = $place;
    }

    public function render()
    {
        return view('livewire.components.table-default', [
            'waterLevels' => DB::table('vwwaterlevels')->where('location', '=', $this->place)->latest('timestamp')->paginate(10),
            'place' => $this->place
        ]);
    }

    public function checkWaterLevel($status)
    {
        // dd([$data, $red, $green, $blue]);
        if ($status == "green") {
            return "GREEN";
        } else if ($status == "yellow") {
            return "YELLOW";
        } else if ($status == "red") {
            return "RED";
        }
    }
}
