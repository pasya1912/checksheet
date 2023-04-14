<select name="value" id="value-{{$area->id}}" class="w-full border border-gray-300 rounded-md shadow-sm ring {{$area->checkdata ? ($area->checkdata->is_good ? 'ring-green-200':'ring-red-200'): 'ring-gray-300'}} focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" {{!isset($area->checkdatta) ? 'disabled':''}}>
    <option value="ok" {{ (isset($area->checkdata) && $area->checkdata->value == 'ok') ? 'selected' : '' }}>Good</option>
    <option value="ng" {{ (isset($area->checkdata) && $area->checkdata->value == 'ng') ? 'selected' : '' }}>Not Good</option>
</select>
<button
onclick="inputData({{ $checksheet->id }},{{ $area->id }},this)"
class="w-4/12 border {{$area->checkdata ? ($area->checkdata->is_good ? 'bg-green-200':'bg-red-200'): 'bg-gray-300'}} p-2 mr-3" {{$area->checkdata ? 'disabled': ''}}>{{$area->checkdata ? ($area->checkdata->is_good ? 'Input':'NG!'): 'Input'}}</button>
