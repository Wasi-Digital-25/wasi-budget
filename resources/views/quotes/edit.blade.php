<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Quote') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('quotes.update', $quote) }}">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block">Number</label>
                            <input type="text" name="number" value="{{ $quote->number }}" class="border rounded w-full" />
                        </div>
                        <div class="mt-4">
                            <button class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
