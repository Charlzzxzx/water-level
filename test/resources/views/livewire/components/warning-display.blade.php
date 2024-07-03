<div>
    <div
        x-data="{ locationIsOpen: @entangle('showConfirm')}"
        x-init="initModals"
        wire:poll.keep-alive.10000ms
    >

        <div x-show="locationIsOpen" class="z-[9999999] p-4 fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
            <!-- Modal container -->
            <div class="flex flex-col space-y-4 items-center w-full md:w-1/4 h-fit bg-white p-4 rounded shadow-md relative">
                <!-- Modal content -->
                <div class="flex flex-col items-center space-y-4">
                    <div class="w-full text-center">
                        <img class="w-500 h-50" src=" {{ asset('/images/warnings.png') }} " alt="" >
                        <p class="text-red-500 font-bold text-2xl"> WARNING ALERT! </p>
                        <p class="uppercase font-semibold"> 
                            @if ($highLocation)
                                {{ $highLocation }} -
                            @else
                                <p> Empty </p>
                            @endif 
                            <span class="text-xl font-semibold text-red-500">
                                @if ($waterLevel)
                                    {{ $waterLevel }} 
                                @else
                                    <p>Empty</p>
                                @endif
                            </span> 
                        </p>
                        <p class="font-semibold"> PLEASE EVACUATE IMMEDIATELY! </p>
                    </div>
                </div>
                <div>
                    <button @click="locationIsOpen = null" class="p-2 font-semibold bg-red-400 text-white rounded-lg shadow-lg hover:bg-red-700"> Close! </button>
                    {{-- <button onclick="playSound()" class="p-2 font-semibold bg-red-400 text-white rounded-lg shadow-lg hover:bg-red-700"> Close! </button> --}}
                </div>
            </div>
        </div>

    </div>
    <script>
        function playSound() {
            var sound = new Howl({
                src: ['/sfx/redalert.mp3'],
                loop: true,
                volume: 50,
            });

            sound.play();
        }
    
        function initModals() {
            Alpine.effect(() => {
                if (this.locationIsOpen) {
                    playSound();A
                }
            });
        }
    </script>
</div>
