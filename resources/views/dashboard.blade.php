<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') . ' ' . Auth::user()->name }}
        </h2>
    </x-slot>
    <div x-data="{
    time: '0 seconds',
    startingTime: 0,
    previousStartingTime: 0,
    init() {
        this.previousStartingTime = localStorage.getItem('previousStartingTime');
        this.startingTime = this.previousStartingTime ? parseInt(this.previousStartingTime) : Date.now();
        if (!this.previousStartingTime) {
            localStorage.setItem('previousStartingTime', this.startingTime);
        }
        this.updateTime();
        setInterval(() => this.updateTime(), 1000);
    },
    updateTime() {
        const timeDifference = Date.now() - this.startingTime;
        const seconds = Math.floor(timeDifference / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        this.time = `${hours} hours, ${minutes % 60} minutes, ${seconds % 60} seconds`;
    },
    resetTimer() {
        this.startingTime = Date.now();
        localStorage.setItem('previousStartingTime', this.startingTime);
        this.updateTime();
    }
}" x-init="init" class="container">
        <p x-text="time"></p>
        <button @click="resetTimer">Reset Timer</button>
    </div>
    <div
        x-data="{ users: ['test', 'test1']}"
        x-init="
            Echo.join('room')
            .here(userss => {
                users = userss
            })
            .joining(user => {
                const result = users.some(item => item.name === user.name);
                if(!result)
                    users.push(user)
            })
            .leaving(user => {
                users = users.filter(item => item.name !== user.name)
            })
        "
        class="container">
        <template x-for="user in users">
            <p class="text-green-500" x-text="user.name"></p>
        </template>
    </div>
    <div
        x-data="{users: ['test']}"
        x-init="
            Echo.private('order.' + {{ Auth::id() }})
            .listen('OrderStatus', (e) => {
                console.log(e);
                document.getElementById(e.id).innerHTML = e.status;
            })"
        class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @foreach($orders as $order)
                <div class="flex items-center justify-between px-5 h-24 w-full bg-white rounded-lg shadow-lg">
                    <strong class="text-lg text-black/50">
                        Order #{{ $order->id }}
                    </strong>
                    <strong>
                        Status: <span id="{{$order->id}}" class="{{ $order->status === 'DISPATCHED' ? 'text-orange-500' : 'text-green-500' }}">
                    {{ $order->status }}
                </span>
                    </strong>
                </div>
            @endforeach
                <div x-data="{users: ['data']}"
                    x-init="

                    let textarea = document.getElementById('textarea');
                    const channel = Echo.private('whiteboard')

                    channel.listenForWhisper('valueChange', (event) => {
                        console.log(event)
                        console.log(textarea)
                        textarea.value = event.value;
                    })



                    textarea.addEventListener('input', (event) => {
                         channel.whisper('valueChange', {
                            value: event.target.value
                         });
                    });
                " class="container bg-white rounded-lg h-96 w-full">
                    <textarea id="textarea" class="w-full h-full resize-none focus:outline-none" name="textarea">

                    </textarea>
                </div>
        </div>
        </div>
</x-app-layout>

<script>
    // // The user open the application and I want to know how long that application is already running
    // // First we need to get the starting time
    // let startingTime = Date.now();
    // // Second we need to set the starting time to our local storage so even the user refresh the page it won't reset
    // let previousStartingTime = localStorage.getItem('previousStartingTime');
    // if(!previousStartingTime) {
    //     localStorage.setItem('previousStartingTime', startingTime);
    // }else {
    //     startingTime = previousStartingTime;
    // }
    // let timeDifference = Date.now() - startingTime;
    //
    // let seconds = Math.floor(timeDifference / 1000);
    // let minutes = Math.floor(seconds / 60);
    // let hours = Math.floor(minutes / 60);
    //
    // let time =`Elapsed time: ${hours} hours, ${minutes % 60} minutes, ${seconds % 60} seconds`;
    // setInterval(() => {
    //     document.getElementById('time').innerText = time;
    // },1000)


    // Third we need to display the time
    // let count = 0;localStorage.
    // setInterval(() => {
    //     // Goal is to get the previous count when the page is refreshed
    //
    //     // If count is zero check if previousCount is set and if it is set the change the value of count to previousCount
    //     let previousCount = localStorage.getItem('previousCount')
    //     if(count === 0 && previousCount)
    //     {
    //         count = previousCount;
    //     }
    //     count++;
    //     localStorage.setItem('previousCount', count)
    //     console.log(Date.now())
    //
    // },1000)
    // // Function to get the stored end time or set a new one
    // function getEndTime() {
    //     let endTime = localStorage.getItem('timerEndTime');
    //     if (!endTime) {
    //         endTime = Date.now() + 60000; // Set timer for 1 minute (60000 ms)
    //         localStorage.setItem('timerEndTime', endTime);
    //     }
    //     return parseInt(endTime);
    // }
    //
    // // Function to update the timer
    // function updateTimer() {
    //     const endTime = getEndTime();
    //     const currentTime = Date.now();
    //     const remainingTime = endTime - currentTime;
    //
    //     if (remainingTime <= 0) {
    //         console.log("Time's up!");
    //         localStorage.removeItem('timerEndTime');
    //     } else {
    //         const seconds = Math.ceil(remainingTime / 1000);
    //         console.log(`${seconds} seconds remaining`);
    //         setTimeout(updateTimer, 1000);
    //     }
    // }
    //
    // // Start the timer
    // updateTimer();
</script>
