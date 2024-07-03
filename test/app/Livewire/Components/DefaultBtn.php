<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BatanganBtn extends Component
{
    public function render()
    {
        return view('livewire.components.btn-update');
    }

    public function createData()
    {
        try {
            // Path to your Python script (sendSms.py)
            //$pythonScriptPath = base_path('../../../../sendSms.py');
            $pythonScriptPath = base_path('sendSms.py');

            // Create a process to run the Python script
            $process = new Process(['python', $pythonScriptPath]);

            // Run the process
            $process->run();

            // Check if the process was successful
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Output the result of the Python script execution
            $output = $process->getOutput();

            // Log the output
            Log::info($output);

            // Display a success message
            session()->flash('success', 'Python script executed successfully.');

        } catch (\Exception $e) {
            // Log any errors
            Log::error($e->getMessage());

            // Display an error message
            session()->flash('error', 'An error occurred while executing the Python script.');
        }
    }
}
