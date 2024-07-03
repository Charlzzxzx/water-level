<?php

namespace App\Livewire;

use App\Models\Location;
use App\Models\Settings;
use App\Models\WaterLevel;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Maps extends Component
{

    public $activeLocation = "sample";

    protected $listeners = ['locationToggled'];

    public $records = array();
    public $tmpMarker = array();

    public function render()
    {

        $marker = json_decode(DB::table('vwformarker')->get(), true);
        $tmpMarker = array();
        $locationArr = json_decode(DB::table('vwlocations')->get(), true);
        foreach ($marker as $m) {
            if (isset($tmpMarker[$m['location']])) {
                continue;
            }
            $tmpMarker[$m['location']] = $m;
        }
        $records = json_decode(DB::table('vwsidediv')->get(), true);
        $this->records = $records;
        $this->tmpMarker = $tmpMarker;
        return view('livewire.maps', ['records' => $records, 'tmpMarker' => $tmpMarker, 'locationArr' => $locationArr]);
    }


    public function toggleLocation($location)
    {
        $this->emit('locationToggled', $location);
    }

    public function recordToggled()
    {
        $records = json_decode(DB::table('vwwaterlevels')->get(), true);
        $this->records = $records;
    }


    public function locationToggled($location)
    {
        // Handle the event to toggle visibility of elements
        // For example, you can set a property to store the active location
        // and use it to conditionally render elements in your Blade template
        $this->activeLocation = ($this->activeLocation === $location) ? "sample" : $location;
    }

    public function store(Request $request)
    {
        $query = DB::table('locations')->where('locationName', '=', $request->location)->get();
        $query = json_decode($query, true);
        if (count($query) > 0) {
            session()->put("errorAdd", true);
            return  redirect("/");
        }
        $newWaterLevel = new Location();
        $newWaterLevel->locationName = $request->location;
        $newWaterLevel->latitude = $request->latitude;
        $newWaterLevel->longitude = $request->longitude;
        $newWaterLevel->longitude = $request->longitude;
        $newWaterLevel->phoneNumber = $request->longitude;

        $isSave = $newWaterLevel->save();
        if ($isSave) {
            $query = DB::table('locations')->where('locationName', '=', $request->location)->get();
            $query = json_decode($query, true);
            $id = $query[0]['locationID'];

            $newSettings = new Settings();
            $newSettings->locationID = $id;
            $newSettings->base = $request->base;
            $newSettings->low = $request->low;
            $newSettings->high = $request->high;
            $newSettings->normal = $request->normal;
            $newSettings->created_at = "NOW()";
            $settingsSave = $newSettings->save();
            if ($settingsSave) {
                session()->put("successAdd", true);
            } else {
                DB::table('locations')->where('locationID', '=', $id)->delete();
                session()->put("errorAdd", true);
            }
        } else {
            session()->put("errorAdd", true);
        }

        return redirect("/");
    }

    public function hotReload()
    {
        $marker = json_decode(DB::table('vwformarker')->get(), true);
        $tmpMarker = array();
        foreach ($marker as $m) {
            if (isset($tmpMarker[$m['location']])) {
                continue;
            }
            $tmpMarker[$m['location']] = $m;
        }
        $records = json_decode(DB::table('vwwaterlevels')->get(), true);
        $this->records = $records;
        $this->tmpMarker = $tmpMarker;
        return [$tmpMarker, $records];
    }

    public function reloadButton()
    {
        $data = $this->hotReload();
        $tmpMarker = $data[0];
        $records = $data[1];
        // Emit an event to update the Livewire component with the new data
        $this->emit('buttonReloaded', ['records' => $records]);
    }

    public function createData()
    {
        $hasError = false;
        try {
            $response = $this->callApi();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $hasError = true;
        } finally {
            if ($hasError == true) {
                session()->put("errorUpdateStatus", true);
            }
            return redirect("/");
        }
    }

    private function callApi(): void
    {
        $client = new Client();
        $response = $client->post('http://127.0.0.1:5000/sendSms', [
            'multipart' => [
                [
                    'name' => 'id',
                    'contents' => 'sample'
                ]
            ]
        ]);
    }
}
