@extends('checksheet.form.layout')
@section('content')
<form method="POST" action="{{ url()->current() }}" class="bg-gray-100 p-6 rounded-lg shadow-md">
    @csrf
    <div class="mb-4">
        <label for="nama" class="block text-gray-700 font-semibold mb-2">Nama:</label>
        <input id="nama" type="text" step="any" name="nama"
            class="w-full border-gray-400 p-2 rounded-lg" required>
    </div>
    <div class="mb-4">
        <label for="value" class="block text-gray-700 font-semibold mb-2">Value:</label>
        <select id="value" name="value" class="w-full border-gray-400 p-2 rounded-lg" required>
            <option value="ok">Good</option>
            <option value="ng">Not Good</option>
        </select>
    </div>
    <div class="mb-4">
        <label for="barang" class="block text-gray-700 font-semibold mb-2">Barang:</label>
        <select id="barang" name="barang" class="w-full border-gray-400 p-2 rounded-lg" required>
            <option value="first">First</option>
            <option value="last">Last</option>
        </select>
    </div>
    <button type="submit"
        class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600">Submit</button>
</form>
@endsection
