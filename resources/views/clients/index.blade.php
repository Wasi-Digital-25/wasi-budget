<x-app-layout>
    <x-slot name="header">Clients</x-slot>
    <div class="py-6">
        <ul>
            @foreach($clients as $client)
                <li>{{ $client->name }}</li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
