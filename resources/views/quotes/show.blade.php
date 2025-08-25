<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quote') }} {{ $quote->number }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p class="mb-4">Status: {{ $quote->status }}</p>
                    <table class="w-full mb-4">
                        <thead>
                            <tr>
                                <th class="px-2 py-1 text-left">Description</th>
                                <th class="px-2 py-1 text-right">Qty</th>
                                <th class="px-2 py-1 text-right">Price</th>
                                <th class="px-2 py-1 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quote->items as $item)
                                <tr>
                                    <td class="border px-2 py-1">{{ $item->description }}</td>
                                    <td class="border px-2 py-1 text-right">{{ $item->quantity }}</td>
                                    <td class="border px-2 py-1 text-right">{{ number_format($item->unit_price_cents/100,2) }}</td>
                                    <td class="border px-2 py-1 text-right">{{ number_format($item->line_total_cents/100,2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p class="font-bold text-right">Total: {{ number_format($quote->total_cents/100,2) }}</p>
                    <div class="mt-4 space-x-2">
                        <form class="inline" method="POST" action="{{ route('quotes.send', $quote) }}">
                            @csrf
                            <button class="px-2 py-1 bg-blue-600 text-white rounded">Send</button>
                        </form>
                        <form class="inline" method="POST" action="{{ route('quotes.accept', $quote) }}">
                            @csrf
                            <button class="px-2 py-1 bg-green-600 text-white rounded">Accept</button>
                        </form>
                        <form class="inline" method="POST" action="{{ route('quotes.reject', $quote) }}">
                            @csrf
                            <button class="px-2 py-1 bg-red-600 text-white rounded">Reject</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
