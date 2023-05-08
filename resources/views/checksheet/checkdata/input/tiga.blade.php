<div class="w-full flex justify-between gap-3">
<input type="text" name="value" id="value-{{$area->id}}" class="w-full border border-gray-300 rounded-md ring {{$area->checkdata ? ($area->checkdata->is_good ? 'ring-green-200':'ring-red-200'): 'ring-gray-300'}} shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" placeholder="Value" value="{{ isset($area->checkdata) ? $area->checkdata->value: '' }}" {{isset($area->checkdata) ? 'disabled' : ''}}>
<button
onclick="inputData({{ $checksheet->id }},{{ $area->id }},this)"
class="w-4/12 border {{$area->checkdata ? ($area->checkdata->is_good ? 'bg-green-200':'bg-red-200'): 'bg-gray-300'}} p-2 mr-3"{{$area->checkdata ? 'disabled': ''}}>{{$area->checkdata ? ($area->checkdata->is_good ? 'Input':'NG!'): 'Input'}}</button>
</div>
@if(isset($area->checkdata) && !$area->checkdata->is_good && $area->checkdata->mark != 1)
<div class="w-full m-auto my-3 {{isset($area->checkdata) ? ($area->checkdata->is_good ? 'hidden':'') : 'hidden'}}">
    @if(isset($area->checkdata->notes))
    <div class="w-full flex justify-start bg-slate-50 py-2 px-1  my-3">
        <span class="text-xs text-start ">{!!$area->checkdata->notes!!}</span>
    </div>
    @endif
    @if(auth()->user()->npk == $area->checkdata?->user || auth()->user()->role == 'admin')
    <button data-modal-target="staticModal" data-modal-toggle="staticModal" onclick="openModal(this,{{$checksheet->id}},{{$area->id}})" data-id="{{$area->checkdata->id}}" class="w-50 ml-auto mr-3 block text-gray-700 bg-orange-300 hover:bg-orange-400 font-light rounded-lg text-sm px-2 py-0.5 text-center " type="button">
    {{(!$area->checkdata->notes) ? "Add notes":"Edit notes"}}
    </button>
    @endif
</div>
@elseif(isset($area->checkdata) && $area->checkdata->mark == 1)
<div class="w-full my-3">
    @if(isset($area->checkdata->notes))
    <div class="w-full  flex justify-start my-3">
        <span class="text-xs text-start">{!!$area->checkdata->notes!!}</span>
    </div>
    @endif
    <div class="w-full flex justify-end">
        <span class="text-xs bg-green-300 rounded-lg px-1 py-0.5 mr-3">Problem Solved</span>
    </div>
</div>
@endif
