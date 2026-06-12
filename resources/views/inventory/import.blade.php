@extends('layouts.app')
@section('title', 'Import Inventory Items')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('inventory.index') }}" class="text-gray-400 hover:text-gray-600">← Back</a>
        <h1 class="text-2xl font-bold text-gray-800">Import Inventory Items</h1>
    </div>

    {{-- Instructions --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">
        <h3 class="font-semibold text-blue-800 mb-2">📋 How to import</h3>
        <ol class="text-sm text-blue-700 space-y-1 list-decimal list-inside">
            <li>Download the template below</li>
            <li>Fill in your items (keep the header row)</li>
            <li>Upload the file (CSV or Excel)</li>
        </ol>

        <div class="mt-4">
            <a href="{{ asset('templates/inventory_import_template.csv') }}" download
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded-lg transition">
                ⬇ Download CSV Template
            </a>
        </div>
    </div>

    {{-- Column guide --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-6">
        <h3 class="font-semibold text-gray-800 mb-3">Column Reference</h3>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr class="text-xs font-semibold text-gray-500 uppercase">
                    <th class="px-3 py-2 text-left">Column</th>
                    <th class="px-3 py-2 text-left">Values</th>
                    <th class="px-3 py-2 text-left">Required</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50 text-xs">
                <tr><td class="px-3 py-2 font-mono">name</td><td class="px-3 py-2">Any text</td><td class="px-3 py-2 text-red-500">Yes</td></tr>
                <tr><td class="px-3 py-2 font-mono">category</td>
                    <td class="px-3 py-2 font-mono text-xs">
                        shawarma_marination<br>
                        mayo_masala_sauces<br>
                        chicken_fish<br>
                        bun_bakery<br>
                        other
                    </td>
                    <td class="px-3 py-2 text-red-500">Yes</td></tr>
                <tr><td class="px-3 py-2 font-mono">unit</td><td class="px-3 py-2">Gms, kg, pkt, Nos, ltr, ml</td><td class="px-3 py-2 text-red-500">Yes</td></tr>
                <tr><td class="px-3 py-2 font-mono">minimum_stock</td><td class="px-3 py-2">Number (e.g. 500)</td><td class="px-3 py-2 text-gray-400">No</td></tr>
                <tr><td class="px-3 py-2 font-mono">unit_price</td><td class="px-3 py-2">Number (e.g. 12.50)</td><td class="px-3 py-2 text-gray-400">No</td></tr>
                <tr><td class="px-3 py-2 font-mono">is_mayo</td><td class="px-3 py-2">1 = yes, 0 = no</td><td class="px-3 py-2 text-gray-400">No</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Upload form --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="font-semibold text-gray-800 mb-4">Upload File</h3>

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                @foreach($errors->all() as $error)
                    <p class="text-sm text-red-700">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('inventory.import.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center hover:border-yellow-400 transition"
                 onclick="document.getElementById('file-input').click()">
                <div class="text-4xl mb-2">📂</div>
                <p class="text-gray-600 font-medium">Click to choose file</p>
                <p class="text-sm text-gray-400 mt-1">CSV or Excel (.xlsx) · Max 2MB</p>
                <p id="file-name" class="text-sm text-yellow-600 font-medium mt-2 hidden"></p>
                <input type="file" id="file-input" name="file"
                       accept=".csv,.xlsx,.xls,.txt"
                       class="hidden"
                       onchange="showFileName(this)">
            </div>

            <button type="submit"
                    class="w-full mt-4 bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-semibold py-2.5 rounded-lg transition">
                Import Items
            </button>
        </form>
    </div>
</div>

<script>
function showFileName(input) {
    const label = document.getElementById('file-name');
    if (input.files.length > 0) {
        label.textContent = '✓ ' + input.files[0].name;
        label.classList.remove('hidden');
    }
}
</script>
@endsection
