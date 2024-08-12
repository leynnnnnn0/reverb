<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div x-data="{ currentPosition: [{x:0, y:0}] }"
        x-init="
        const channel = Echo.private('app')
        let isMouseDown = false;
        channel.listenForWhisper('onmousemove', (event) => {

        })
        const myBox = document.getElementById('myBox')
        console.log(myBox);
        const x = window.innerWidth;
        const y = window.innerHeight;
        myBox.addEventListener('mousedown', (e) => {
            console.log('e: ' + e.x)
            isMouseDown = true
            currentPosition = [
                {
                    x: e.x,
                    y: e.y
                }
            ]

        })
        document.addEventListener('mousedown', (e) => {
            console.log(e.x);
        })
        document.addEventListener('mouseup', (e) => {
            isMouseDown = false
        })
        onmousemove = (e) => {
            if(isMouseDown){
                currentPosition = [
                {
                    x: e.x,
                    y: e.y
                }
            ]
            }

        }
    "
        class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            <div class="flex gap-10 p-5 h-[500px] w-full bg-white rounded-lg shadow-lg">

                    <div
                        id="myBox"
                        class="absolute h-52 w-52 bg-red-500 rounded-lg"
                        x-bind:style="
                        `left: ${currentPosition[0].x}px; top: ${currentPosition[0].y}px`
                    ">
                        <p x-text="`left: ${currentPosition[0].x}px; top: ${currentPosition[0].y}px`"></p>

                    </div>

            </div>
        </div>
    </div>
</x-app-layout>

