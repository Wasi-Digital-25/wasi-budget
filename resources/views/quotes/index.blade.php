<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Quotes') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($quotes->count())
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">Number</th>
                                    <th class="px-4 py-2 text-left">Client</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($quotes as $quote)
                                    <tr>
                                        <td class="border px-4 py-2">
                                            <a href="{{ route('quotes.show', $quote) }}" class="text-blue-600">{{ $quote->number }}</a>
                                        </td>
                                        <td class="border px-4 py-2">{{ $quote->client_name }}</td>
                                        <td class="border px-4 py-2">{{ $quote->status }}</td>
                                        <td class="border px-4 py-2 text-right">{{ number_format($quote->total_cents/100, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $quotes->links() }}
                        </div>
                    @else
                        <p class="text-center text-gray-500">No quotes found.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
