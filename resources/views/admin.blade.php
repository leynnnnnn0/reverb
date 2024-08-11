<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @foreach($orders as $order)
                <div class="flex items-center justify-between px-5 h-24 w-full bg-white rounded-lg shadow-lg">
                    <strong class="text-lg text-black/50">
                        Order #{{ $order->id }}
                    </strong>
                    <strong>
                        Status:
                        <select name="status" id="status-{{ $order->id }}" data-order-id={{ $order->id }}>
                            @php
                                $status = ['DISPATCHED', 'DELIVERED']
                            @endphp
                            @foreach($status as $stat)
                                <option value="{{ $stat }}" {{ $stat === $order->status ? 'selected' : '' }}>
                                    {{ $stat }}
                                </option>
                            @endforeach
                        </select>
                    </strong>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelects = document.querySelectorAll('select[name="status"]');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        statusSelects.forEach(select => {
            select.addEventListener('change', async function(event) {
                const orderId = this.getAttribute('data-order-id');
                const newStatus = this.value;

                await fetch(`/status-change/${orderId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({status: newStatus})
                }).then(response => {
                    const result = response.json();
                    console.log(result);
                })
                    .catch(err => console.log(err))
            });
        });
    });
</script>
