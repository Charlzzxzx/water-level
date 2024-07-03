<?php

namespace App\Livewire\Components;

use Livewire\Component;
use App\Models\WaterLevel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WarningDisplay extends Component
{
    public $batanganConfirm, $lumbayaoConfirm, $currentTime, $showConfirm;

    public function render()
    {
        // 
        $this->currentTime = Carbon::now()->format('H:i:s');

        $distinctLocation = json_decode(DB::table('vwlocations')->get(), true);


        $highLocation = "";
        $waterLevel = 0.0;
        $tmpArray = array();
        foreach ($distinctLocation as $d) {
            $isCheck = DB::table('vwwaterlevels')->where('location', '=', $d['location'])->latest('timestamp')->first();
            $this->showConfirm = ($isCheck !== null && $isCheck->status == "red");
            if ($this->showConfirm) {
                $highLocation = $d['location'];
                $waterLevel = $isCheck->water_level;
                break;
            }
        }

        // pass the variables into the html
        return view('livewire.components.warning-display', [
            'currentTime' => $this->currentTime,
            'isCheck' => $isCheck ?? null,
            'highLocation' => $highLocation,
            'waterLevel' => $waterLevel
        ]);
    }
}
