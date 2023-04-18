<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checksheet Input Data') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="p-6 bg-white shadow-sm sm:rounded-lg w-full ">
                <div class="w-full  my-5">

                    <form action="{{ route('checksheet.list') }}" method="GET" id="search-checksheet" class="flex gap-3 w-full">
                        <div class="w-full">
                            <label for="line">Line</label>
                            <select id="line" name="line" class=" w-full border-gray-400 p-2 rounded-lg" required>
                                <option value="" selected>Line</option>
                                @foreach($lineList as $line)
                                <option value="{{$line->line}}" {{request()->get('line') == $line->line ? 'selected':''}}>{{$line->line}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full">
                            <label for="code">Model</label>
                            <select id="code" name="code" class="w-full border-gray-400 p-2 rounded-lg" required>
                                <option value="" selected >Model</option>
                                @foreach($codeList as $code)
                                <option value="{{$code->code}}" {{request()->get('code') == $code->code ? 'selected':''}}>{{$code->code}}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="w-full">
                            <label for="cell">Cell</label>
                            <select id="cell" name="cell" class="w-full border-gray-400 p-2 rounded-lg" required>
                                <option value="m1" {{request()->get('cell') == 'm1' ? 'selected':''}}>m1</option>
                                <option value="m2" {{request()->get('cell') == 'm2' ? 'selected':''}}>m2</option>
                            </select>
                        </div>
                        <div class="w-full">
                            <label for="shift">Shift</label>
                            <select id="shift" name="shift" class="w-full border-gray-400 p-2 rounded-lg" required>
                                <option value="1" {{request()->get('shift') == '1' ? 'selected':''}}>1</option>
                                <option value="2" {{request()->get('shift') == '2' ? 'selected':''}}>2</option>
                                <option value="3" {{request()->get('shift') == '3' ? 'selected':''}}>3</option>
                                <option value="1-long" {{request()->get('shift') == '1-long' ? 'selected':''}}>1-long</option>
                                <option value="3-long" {{request()->get('shift') == '3-long' ? 'selected':''}}>3-long</option>

                            </select>
                        </div>
                        <div class="w-full">
                            <label for="barang">First/last</label>
                            <select id="barang" name="barang" class="w-full border-gray-400 p-2 rounded-lg" required>
                                <option value="first" {{request()->get('barang') == 'first' ? 'selected':''}}>first</option>
                                <option value="last" {{request()->get('barang') == 'last' ? 'selected':''}}>last</option>
                            </select>
                        </div>
                    </form>

                </div>
                <div class="w-full sm:overflow-x-scroll">
                    <table class="table-auto w-full  border border-red-500 text-center">
                        <thead class="border border-red-400">
                            <tr>
                                <th>Line</th>
                                <th>Code</th>
                                <th>Nama</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="border border-red-200 ">
                            @if(!empty($checkList['data']))
                            @foreach ($checkList['data'] as $check)
                            <tr class="{{(int)$check->notgood ? ($check->notgood > 0 ? 'hover:bg-red-200   ':'hover:bg-green-200'):'hover:bg-green-200' }}  transition duration-300 ease-in-out py-5">

                                <td class="py-5"><a onload="changeid(this)" href="{{ route('checksheet.area', $check->id) }}?{{ request()->getQueryString() }}  ">{{ $check->line }}</a></td>
                                <td class="py-5"><a onload="changeid(this)" href="{{ route('checksheet.area', $check->id) }}?{{ request()->getQueryString() }}  ">{{ $check->code }}</a></td>
                                <td class="py-5">
                                    <a onload="changeid(this)" href="{{ route('checksheet.area', $check->id) }}?{{ request()->getQueryString() }}  ">{{ $check->nama }}</a>
                                </td>
                                <td class="py-5 flex justify-evenly">
                                    <div class="mx-5 w-1/4 {{(int)$check->notgood ? ($check->notgood > 0 ? 'bg-red-400':'bg-green-400'):'bg-green-400' }} text-gray-800"><a href="{{route('checksheet.data')}}?min_tanggal={{date('Y-m-d')}}&max_tanggal={{date('Y-m-d')}}&line={{ $check->line }}&code={{ $check->code }}&checksheet={{ $check->id }}">{{(int)$check->notgood ? ($check->notgood > 0 ? $check->notgood.' NG!':'NO NG'):'NO NG' }}</a></div>
                                    <div class="mx-5 w-1/2 bg-green-300 text-gray-500">Approved</div>
                                </td>

                            </tr>
                            @endforeach
                            @elseif($checkList == '500')
                            <tr>
                                <td colspan="4" class="py-5">Isi Line Terlebih Dahulu</td>
                            </tr>
                            @elseif($checkList == '400')
                            <tr>
                                <td colspan="4" class="py-5">Isi Code Terlebih Dahulu</td>
                            </tr>
                            @else
                            <tr>
                                <td colspan="4" class="py-5">Data Kosong</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="flex items-center justify-center space-x-4">

                    @if($checkList != '500' && $checkList != '400')
                    @foreach ($checkList['links'] as $link)
                    @if($link['url'] == null)
                    @continue
                    @endif
                    <a href="{{ $link['url'] }}" class="px-3 py-2 text-gray-700 hover:text-white hover:bg-gray-700 rounded {{ $link['active'] ? 'bg-orange-400' : '' }}">
                        <?= $link['label'] ?>
                    </a>
                    @endforeach
                    @endif
                </div>



            </div>
        </div>
    </div>
@section('script')
<script>

var formes = document.getElementById('search-checksheet');
//get selected value from selected option form then build a query string
var line = document.getElementById('line');
var code = document.getElementById('code');
var cell = document.getElementById('cell');
var shift = document.getElementById('shift');
var barang = document.getElementById('barang');


formes.addEventListener('change', function() {
    formes.submit();
});
</script>
@endsection
</x-app-layout>

