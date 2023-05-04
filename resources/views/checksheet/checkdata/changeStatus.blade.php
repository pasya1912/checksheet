

@if($data->approval == "approved")
<?php $approved = true;?>
<?php $color = "bg-green-500";?>
@elseif($data->approval == "rejected")
<?php $rejected = true;?>
<?php $color = "bg-red-500";?>
@elseif($data->approval == "wait")
<?php $wait = true;?>
<?php $color = "bg-yellow-400";?>
@endif

<select onchange="changeStatus(this)" data-id="{{$data->id}}" name="changeStatus" class="w-full border-gray-400 p-2 rounded-lg text-start appearance-none md:text-center text-xs md:text-base" required>
    <option value="approved" {{isset($approved) ? 'selected':''}}>approved</option>
    <option value="rejected" {{isset($rejected) ? 'selected':''}}>rejected</option>
    <option value="wait" {{isset($wait) ? 'selected':''}}>wait</option>
</select>

