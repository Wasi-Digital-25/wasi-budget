<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('New Quote') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900" x-data="{
                    items: [{description:'', qty:1, price:0}],
                    addItem(){ this.items.push({description:'', qty:1, price:0}); },
                    removeItem(i){ this.items.splice(i,1); },
                    total(){ return this.items.reduce((t,i)=>t + (i.qty*i.price),0); }
                }">
                    <form method="POST" action="{{ route('quotes.store') }}">
                        @csrf
                        <div>
                            <label class="block">Number</label>
                            <input type="text" name="number" class="border rounded w-full" />
                        </div>
                        <div class="mt-4">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="px-2 py-1 text-left">Description</th>
                                        <th class="px-2 py-1 text-right">Qty</th>
                                        <th class="px-2 py-1 text-right">Price</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in items" :key="index">
                                        <tr>
                                            <td class="border px-2 py-1">
                                                <input type="text" class="w-full border-none" x-model="item.description" :name="`items[${index}][description]`" />
                                            </td>
                                            <td class="border px-2 py-1">
                                                <input type="number" step="0.01" class="w-full border-none text-right" x-model.number="item.qty" :name="`items[${index}][qty]`" />
                                            </td>
                                            <td class="border px-2 py-1">
                                                <input type="number" step="0.01" class="w-full border-none text-right" x-model.number="item.price" :name="`items[${index}][price]`" />
                                            </td>
                                            <td class="border px-2 py-1 text-center">
                                                <button type="button" @click="removeItem(index)" class="text-red-600">&times;</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                            <div class="mt-2">
                                <button type="button" class="px-2 py-1 bg-gray-200 rounded" @click="addItem()">Add Item</button>
                            </div>
                        </div>
                        <div class="mt-4 font-bold">
                            Total: <span x-text="total().toFixed(2)"></span>
                        </div>
                        <div class="mt-4">
                            <button class="px-4 py-2 bg-blue-600 text-white rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
