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
                    <p>Status: {{ $quote->status }}</p>
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
