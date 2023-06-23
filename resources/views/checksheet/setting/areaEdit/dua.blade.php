
    <div>
        <label for="min" class="block text-sm font-medium text-gray-700">Min</label>
        <div class="mt-1 w-max-full">
            <input type="number" name="min" id="min" autocomplete="given-name" step="any"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                value="{{ $area->min ?? ''}}">
        </div>
    </div>
    <div>
        <label for="tengah" class="block text-sm font-medium text-gray-700">Nilai Tengah</label>
        <div class="mt-1 w-max-full">
            <input type="number" name="tengah" id="tengah" autocomplete="given-name" step="any"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                value="{{ $area->tengah ?? ''}}">
        </div>
    </div>
    <div>
        <label for="max" class="block text-sm font-medium text-gray-700">Max</label>
        <div class="mt-1 w-max-full">
            <input type="number" name="max" id="max" autocomplete="given-name" step="any"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                value="{{ $area->max ?? ''}}">
        </div>
    </div>

