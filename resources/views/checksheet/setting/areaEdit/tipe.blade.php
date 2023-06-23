<div>
    <label for="tipe" class="block text-sm font-medium text-gray-700">Tipe</label>
    <div class="mt-1 w-max-full">
        <select id="tipe" name="tipe" autocomplete="tipe" onchange="getInput(this)"
            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
            <option value="1" {{$area->tipe == 1 ? 'selected' : ''}}>OK / NG</option>
            <option value="2" {{$area->tipe == 2 ? 'selected' : ''}}>Standard Min/Max</option>
            <option value="3" {{$area->tipe == 3 ? 'selected' : ''}}>Normal Input</option>
        </select>
    </div>
</div>
<div id="tipeInput">
@if($area->tipe == 1)
@elseif($area->tipe == 2)
    @include('checksheet.setting.areaEdit.dua')
@elseif($area->tipe == 3)
@endif
</div>
