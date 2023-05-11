

@if($data->approval == "4")
<?php $approved = true;?>
<?php $color = "bg-green-500";?>
@elseif($data->approval == "0")
<?php $wait = true;?>
<?php $color = "bg-gray-400";?>
@else
<?php $rejected = true;?>
<?php $color = "bg-yellow-500";?>
@endif

<select onchange="changeStatus(this)" data-id="{{$data->id}}" name="changeStatus" class="w-full border-gray-400 p-2 rounded-lg text-start appearance-none md:text-center text-xs md:text-base" required>
    <option value="approved" >approved</option>
    <option value="rejected">rejected</option>
    <option value="wait">wait</option>
</select>

