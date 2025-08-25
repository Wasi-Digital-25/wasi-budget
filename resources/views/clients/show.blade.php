<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $client->name }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-2">
                    <p><strong>Email:</strong> {{ $client->email }}</p>
                    <p><strong>Phone:</strong> {{ $client->phone }}</p>
                    <p><strong>Tax ID:</strong> {{ $client->tax_id }}</p>
                    <p><strong>Address:</strong> {{ $client->address }}</p>
                    <p><strong>Notes:</strong> {{ $client->notes }}</p>
                    <a href="{{ route('clients.edit', $client) }}" class="text-blue-600">Edit</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

