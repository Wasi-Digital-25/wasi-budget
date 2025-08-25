<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Clients</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 font-medium text-sm text-green-600">{{ session('success') }}</div>
            @endif

            <div class="flex justify-end mb-4">
                <a href="{{ route('clients.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Add Client</a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($clients->count())
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">Name</th>
                                    <th class="px-4 py-2 text-left">Email</th>
                                    <th class="px-4 py-2 text-left">Phone</th>
                                    <th class="px-4 py-2 text-left">TaxID</th>
                                    <th class="px-4 py-2 text-left">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($clients as $client)
                                    <tr>
                                        <td class="border px-4 py-2">{{ $client->name }}</td>
                                        <td class="border px-4 py-2">{{ $client->email }}</td>
                                        <td class="border px-4 py-2">{{ $client->phone }}</td>
                                        <td class="border px-4 py-2">{{ $client->tax_id }}</td>
                                        <td class="border px-4 py-2 space-x-2">
                                            <a href="{{ route('clients.edit', $client) }}" class="text-blue-600">Edit</a>
                                            <form method="POST" action="{{ route('clients.destroy', $client) }}" class="inline" onsubmit="return confirm('Delete this client?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $clients->links() }}</div>
                    @else
                        <p class="mb-4">No clients found.</p>
                        <a href="{{ route('clients.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Add Client</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

