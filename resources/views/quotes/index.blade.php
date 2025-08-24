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
                    <table class="min-w-full">
                        <thead>
                            <tr>
                                <th class="px-4 py-2">#</th>
                                <th class="px-4 py-2">Number</th>
                                <th class="px-4 py-2">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quotes as $quote)
                                <tr>
                                    <td class="border px-4 py-2">{{ $quote->id }}</td>
                                    <td class="border px-4 py-2">
                                        <a href="{{ route('quotes.show', $quote) }}" class="text-blue-600">{{ $quote->number }}</a>
                                    </td>
                                    <td class="border px-4 py-2">{{ $quote->status }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $quotes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
