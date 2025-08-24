<x-app-layout>
    <x-slot name="header">New Client</x-slot>
    <form method="post" action="{{ route('clients.store') }}" class="space-y-4">
        @csrf
        <div>
            <label class="block">Name</label>
            <input name="name" class="border" />
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white">Save</button>
    </form>
</x-app-layout>
