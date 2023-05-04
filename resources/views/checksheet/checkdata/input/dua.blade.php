<div class="w-full flex justify-between gap-3">
    <input type="number" name="value" id="value-{{$area->id}}" class="w-full border border-gray-300 rounded-md shadow-sm ring {{$area->checkdata ? ($area->checkdata->is_good ? 'ring-green-200' : 'ring-red-200'): 'ring-gray-300'}} focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Value" value="{{ isset($area->checkdata) ? $area->checkdata->value : '' }}" {{isset($area->checkdata) ? 'disabled' : ''}}>
<button
onclick="inputData({{ $checksheet->id }} , {{ $area->id }},this)"
class="w-4/12 border {{$area->checkdata ? ($area->checkdata->is_good ? 'bg-green-200':'bg-red-200') : 'bg-gray-300'}} p-2 mr-3" {{$area->checkdata ? 'disabled': ''}}>{{$area->checkdata ? ($area->checkdata->is_good ? 'Good':'NG!'): 'Input'}}</button>
</div>
<div class="w-full m-auto my-3 {{isset($area->checkdata) ? ($area->checkdata->is_good ? 'hidden':'') : 'hidden'}}">
    <button data-modal-target="staticModal" data-modal-toggle="staticModal" onclick="openModal(this)" data-id="{{$area->id}}" class="w-50 ml-auto mr-3 block text-gray-700 bg-orange-300 hover:bg-orange-400 font-light rounded-lg text-sm px-2 py-0.5 text-center " type="button">
        Add notes
      </button>
</div>

