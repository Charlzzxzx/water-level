<div>
    <div wire:poll.keep-alive>
        <div
            class="max-w-screen-md mx-auto sm:rounded-lg bg-white border shadow-lg rounded-lg relative overflow-x-auto shadow-md sm:rounded-lg">
            <table class="w-full bg-white text-sm text-left rtl:text-right text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-300 font-semibold text-center">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-semibold">
                            Date & Time
                        </th>
                        <th scope="col" class="px-6 py-3">
                            STATUS
                        </th>
                        <th scope="col" class="px-6 py-3">
                            {{ $place }} - Water Level
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($waterLevels as $level)
                        <tr class="even:bg-gray-100 border-b">
                            <td class="px-6 py-4 text-center">
                                <span> {{ $level->timestamp }} </span>
                            </td>
                            <td class="px-6 py-4 text-center"
                                style="color:{{ $this->checkWaterLevel($level->status) }}">
                                {{ $this->checkWaterLevel($level->status) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                {{ $level->water_level }}cm
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
            <div class="px-3">
                {{ $waterLevels->links() }}
            </div>
        </div>
    </div>
</div>
