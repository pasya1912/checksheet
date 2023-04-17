
@if($area->tipe == '2')
    @include('checksheet.checkdata.input.dua')
@elseif($area->tipe == '3')
    @include('checksheet.checkdata.input.tiga' )
@else
    @include('checksheet.checkdata.input.satu')
@endif