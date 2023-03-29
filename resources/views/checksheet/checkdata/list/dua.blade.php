@extends('checksheet.checkdata.layout')
@section('content')
<div class="text-center">
    <h3>Standar</h3>
    <p>Min:{{$checksheetarea->min ?? "Tidak ada minimal"}} - Max: {{$checksheetarea->max ?? "Tidak ada maximal"}}</p>
</div>
@endsection
