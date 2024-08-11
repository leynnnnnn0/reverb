<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') . ' ' . Auth::user()->name }}
        </h2>
    </x-slot>
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
{{--                <div x-data="{usersList: []}"--}}
{{--                     x-init="--}}
{{--                        Echo.join('room')--}}
{{--                        .here(users => {--}}
{{--                            usersList = users--}}
{{--                        })--}}
{{--                        .joining(user => {--}}
{{--                            console.log(user.name + ' has joined')--}}
{{--                        })--}}
{{--                     "--}}
{{--                     class="container">--}}
{{--                    <template x-for="user in usersList">--}}
{{--                        <p class="text-lg text-green-500 font-bold" x-text="user.name"></p>--}}
{{--                    </template>--}}
{{--                </div>--}}
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
