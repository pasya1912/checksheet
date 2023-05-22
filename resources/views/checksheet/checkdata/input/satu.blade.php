<div class="w-full flex justify-evenly gap-3">
    <input type="hidden" id="value-{{ $area->id }}" value="{{ isset($area->checkdata) ? $area->checkdata->value: '' }}">
    @if(isset($area->checkdata))
        @if($area->checkdata->is_good)
            <button onclick="inputData({{ $checksheet->id }},{{ $area->id }},{{$area->tipe}},this)" id="okbutton-{{$area->id}}" value="ok" class="w-4/12 border p-2 mr-3 bg-green-300" disabled>OK</button>
        @else
            <button onclick="inputData({{ $checksheet->id }},{{ $area->id }},{{$area->tipe}},this)" id="ngbutton-{{$area->id}}" value="ng" class="w-4/12 border p-2 mr-3 bg-red-300" disabled>NG</button>
        @endif
    @else
        <button onclick="inputData({{ $checksheet->id }},{{ $area->id }},{{$area->tipe}},this)" id="okbutton-{{$area->id}}" value="ok" class="w-4/12 border p-2 mr-3 bg-gray-300 ">OK</button>
        <button onclick="inputData({{ $checksheet->id }},{{ $area->id }},{{$area->tipe}},this)" id="ngbutton-{{$area->id}}" value="ng" class="w-4/12 border p-2 mr-3 bg-gray-300"><span class="">NG</span></button>
    @endif
</div>
@if(isset($area->checkdata) && !$area->checkdata->is_good && $area->checkdata->mark != 1)
<div class="w-full m-auto my-3 {{isset($area->checkdata) ? ($area->checkdata->is_good ? 'hidden':'') : 'hidden'}}">
    @if(isset($area->checkdata->notes))
    <div class="w-full flex justify-start bg-slate-50 py-2 px-1  my-3">
        <span class="text-xs text-start ">{!!$area->checkdata->notes!!}</span>
    </div>
    @endif
    @if(auth()->user()->npk == $area->checkdata?->user)
    <button data-modal-target="staticModal" data-modal-toggle="staticModal" onclick="openModal(this,{{$checksheet->id}},{{$area->id}},{{$area->tipe}})" data-id="{{$area->checkdata->id}}" class="w-50 ml-auto mr-3 block text-gray-700 bg-orange-300 hover:bg-orange-400 font-light rounded-lg text-sm px-2 py-0.5 text-center " type="button">
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
    <div class="w-full flex justify-between">
        <span class="text-xs bg-gray-300 shadow-xs shadow-black rounded-lg px-1 py-0.5 mr-3">Revised value: <span class="uppercase">{{$area->checkdata->revised_value}}</span></span>
        <span class="text-xs bg-green-300 rounded-lg px-1 py-0.5 mr-3">Problem Solved</span>
    </div>
</div>
@endif
