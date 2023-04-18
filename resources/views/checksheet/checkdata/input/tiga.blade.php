<input type="text" name="value" id="value-{{$area->id}}" class="w-full border border-gray-300 rounded-md ring {{$area->checkdata ? ($area->checkdata->is_good ? 'ring-green-200':'ring-red-200'): 'ring-gray-300'}} shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Value" value="{{ isset($area->checkdata) ? $area->checkdata->value: '' }}" {{isset($area->checkdata) ? 'disabled' : ''}}>
<button
onclick="inputData({{ $checksheet->id }},{{ $area->id }},this)"
class="w-4/12 border {{$area->checkdata ? ($area->checkdata->is_good ? 'bg-green-200':'bg-red-200'): 'bg-gray-300'}} p-2 mr-3"{{$area->checkdata ? 'disabled': ''}}>{{$area->checkdata ? ($area->checkdata->is_good ? 'Input':'NG!'): 'Input'}}</button>
