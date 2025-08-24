<x-app-layout>
    <x-slot name="header">Edit Client</x-slot>
    <form method="post" action="{{ route('clients.update', $client) }}" class="space-y-4">
        @csrf
        @method('PUT')
        <div>
            <label class="block">Name</label>
            <input name="name" value="{{ old('name', $client->name) }}" class="border" />
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white">Update</button>
    </form>
</x-app-layout>
