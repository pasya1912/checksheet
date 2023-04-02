<select name="value" id="value" class="w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" >
    <option value="ok" {{ (isset($area->checkdata) && $area->checkdata->value == 'ok') ? 'selected' : '' }}>Good</option>
    <option value="ng" {{ (isset($area->checkdata) && $area->checkdata->value == 'ng') ? 'selected' : '' }}>Not Good</option>
</select>