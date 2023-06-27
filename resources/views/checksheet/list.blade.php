@section('title', 'Input')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Checksheet Input') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="p-6 bg-white shadow-sm sm:rounded-lg w-full ">
                <div class="w-full  my-5">
                    <form class="flex gap-3 flex-col md:flex-row justify-center md:justify-end mb-5 w-full ml-auto  md:w-4/12" id="setJP" method="POST" action="{{route('checksheet.setJP')}}">
                        @csrf
                        <div class="w-full">
                            <label for="jp">PIC JP</label>
                            <select id="jp" name="jp" class=" w-full border-gray-400 p-2 rounded-lg"
                                required>
                                <option value="" selected>JP</option>
                                @foreach ($jpies as $jp)
                                    <option value="{{ $jp->npk }}"
                                        {{ $jp->npk == request()->session()->get('jp') ? 'selected' : '' }}>
                                        {{ $jp->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-3/12 flex items-end">
                            <input type="submit" value="Submit" class="w-6/12 md:w-full mx-auto cursor-pointer bg-gray-400 text-white bg-opacity-90 p-2 rounded-lg">
                        </div>
                    </form>
                    <hr>
                    <form action="{{ route('checksheet.list') }}" method="GET" id="search-checksheet"
                        class=" w-full">
                        <div class="flex gap-3 flex-col md:flex-row">
                            <div class="w-full">
                                <label for="line">Line</label>
                                <select id="line" name="line" class=" w-full border-gray-400 p-2 rounded-lg"
                                    required>
                                    <option value="" selected>Line</option>
                                    @foreach ($lineList as $line)
                                        <option value="{{ $line->line }}"
                                            {{ request()->get('line') == $line->line ? 'selected' : '' }}>
                                            {{ $line->line }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-full">
                                <label for="code">Model</label>
                                <select id="code" name="code" class="w-full border-gray-400 p-2 rounded-lg"
                                    required>
                                    <option value="" selected>Model</option>
                                    @foreach ($codeList as $code)
                                        <option value="{{ $code->code }}"
                                            {{ request()->get('code') == $code->code ? 'selected' : '' }}>
                                            {{ $code->code }}
                                        </option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="w-full">
                                <label for="cell">Cell</label>
                                <select id="cell" name="cell" class="w-full border-gray-400 p-2 rounded-lg"
                                    required>
                                    <option value="m1" {{ request()->get('cell') == 'm1' ? 'selected' : '' }}>m1
                                    </option>
                                    <option value="m2" {{ request()->get('cell') == 'm2' ? 'selected' : '' }}>m2
                                    </option>
                                </select>
                            </div>
                            <div class="w-full">
                                <label for="shift">Shift</label>
                                <select id="shift" name="shift" class="w-full border-gray-400 p-2 rounded-lg"
                                    required>
                                    <option value="1" {{ request()->get('shift') == '1' ? 'selected' : '' }}>1
                                    </option>
                                    <option value="2" {{ request()->get('shift') == '2' ? 'selected' : '' }}>2
                                    </option>
                                    <option value="3" {{ request()->get('shift') == '3' ? 'selected' : '' }}>3
                                    </option>
                                    <option value="1-long" {{ request()->get('shift') == '1-long' ? 'selected' : '' }}>
                                        1-long
                                    </option>
                                    <option value="3-long" {{ request()->get('shift') == '3-long' ? 'selected' : '' }}>
                                        3-long
                                    </option>

                                </select>
                            </div>
                            <div class="w-full">
                                <label for="barang">Urutan</label>
                                <select id="barang" name="barang" class="w-full border-gray-400 p-2 rounded-lg"
                                    required>
                                    <option value="first" {{ request()->get('barang') == 'first' ? 'selected' : '' }}>
                                        first
                                    </option>
                                    <option value="middle"
                                        {{ request()->get('barang') == 'middle' ? 'selected' : '' }}>
                                        middle
                                    </option>
                                    <option value="last" {{ request()->get('barang') == 'last' ? 'selected' : '' }}>
                                        last
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="w-full overflow-scroll">
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
                            @if (!empty($checkList['data']))
                                @foreach ($checkList['data'] as $check)
                                    <tr
                                        class="{{ $check->status == 'DONE-OK' ? 'bg-green-100' : ($check->status == 'DONE-NG' || $check->status == 'PROGRESS-NG' ? 'bg-red-100' : ($check->status == 'PROGRESS-OK' ? 'bg-yellow-100' : '')) }}  transition duration-300 ease-in-out py-5 border border-gray-700">

                                        <td class="py-5"><a onload="changeid(this)"
                                                href="{{ route('checksheet.area', $check->id) }}?{{ request()->getQueryString() }}  ">{{ $check->line }}</a>
                                        </td>
                                        <td class="py-5"><a onload="changeid(this)"
                                                href="{{ route('checksheet.area', $check->id) }}?{{ request()->getQueryString() }}  ">{{ $check->code }}</a>
                                        </td>
                                        <td class="py-5">
                                            <div class="w-full">
                                                <a onload="changeid(this)"
                                                    href="{{ route('checksheet.area', $check->id) }}?{{ request()->getQueryString() }}  ">{{ $check->nama }}</a>
                                            </div>
                                            <div class="w-full">
                                                <span
                                                    class="bg-gray-200 rounded-md px-3 py-1 shadow-gray-50 shadow-sm">{{ $check->all }}
                                                    area</span>
                                            </div>
                                        </td>
                                        <td class="py-5">
                                            <div class="flex justify-evenly gap-2">
                                                <div class="w-full md:w-1/3">
                                                    <div>Check Status</div>
                                                    <div
                                                        class="px-1 {{ $check->status == 'DONE-OK' ? 'bg-green-300' : ($check->status == 'DONE-NG' ? 'bg-red-300' : ($check->status == 'PROGRESS-NG' ? 'bg-red-300' : ($check->status == 'NOT-STARTED' ? 'bg-white' : 'bg-yellow-300'))) }} text-gray-800 rounded-sm shadow-sm shadow-black">
                                                        <a
                                                            href="{{ route('checksheet.data') }}?min_tanggal={{ date('d-m-Y') }}&max_tanggal={{ date('d-m-Y') }}&line={{ $check->line }}&code={{ $check->code }}&checksheet={{ $check->id }}&cell={{ $query->cell }}&shift={{ $query->shift }}&barang={{ $query->barang }}">{{ $check->status == 'DONE-OK' ? 'OK' : ($check->status == 'DONE-NG' || $check->status == 'PROGRESS-NG' ? $check->notgood . ' NG!' : ($check->status == 'PROGRESS-OK' ? 'Proses' : 'Belum Mulai')) }}</a>

                                                    </div>

                                                </div>
                                                <div class="w-full md:w-1/3">
                                                    <div>Approval</div>
                                                    <div
                                                        class="w-full px-1 mr-1 text-gray-500 rounded-sm shadow-sm shadow-black {{ $check->approval > 0 ? 'bg-green-200' : 'bg-gray-200' }}">
                                                        {{ $check->approval > 0 ? 'Approved' : 'Not Yet' }}</div>
                                                </div>
                                            </div>
                                        </td>


                                    </tr>
                                @endforeach

                                @elseif($checkList == '700')
                                <tr>
                                    <td colspan="4" class="py-5">Tentukan JP Terlebih dahulu</td>
                                </tr>
                                @elseif($checkList == '705')
                                <tr>
                                    <td colspan="4" class="py-5">JP Tidak valid silahkan pilih ulang</td>
                                </tr>
                                @elseif($checkList == '500')
                                <tr>
                                    <td colspan="4" class="py-5">Isi Line Terlebih Dahulu</td>
                                </tr>
                            @elseif($checkList == '400')
                                <tr>
                                    <td colspan="4" class="py-5">Isi Code Terlebih Dahulu</td>
                                </tr>
                            @elseif($checkList == 300)
                                <tr>
                                    <td colspan="4" class="py-5">Missing Required Parameter</td>
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

                    @if (isset($checkList['data']) && count($checkList['data']) > 1)
                        @foreach ($checkList['links'] as $link)
                            @if ($link['url'] == null)
                                @continue
                            @endif
                            <a href="{{ $link['url'] }}"
                                class="px-3 py-2 text-gray-700 hover:text-white hover:bg-gray-700 rounded {{ $link['active'] ? 'bg-orange-400' : '' }}">
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
            var setJP = document.getElementById('setJP');
            //get selected value from selected option form then build a query string
            var line = document.getElementById('line');
            var code = document.getElementById('code');
            var cell = document.getElementById('cell');
            var shift = document.getElementById('shift');
            var barang = document.getElementById('barang');

            var jp = document.getElementById('jp');

            //if code cell shift barang change then submit form
            line.addEventListener('change', function(value) {
                console.log('line change');
                //call xhr request to checksheet.getCode
                var xhr = new XMLHttpRequest();
                xhr.open('GET', '{{ route('checksheet.getCode') }}?line=' + line.value);
                xhr.onload = function() {
                    if (xhr.status === 200) {
                        var res = JSON.parse(xhr.responseText);
                        var option = '<option value="" selected disabled>Model</option>';
                        for (var i = 0; i < res.data.length; i++) {
                            option += '<option value="' + res.data[i].code + '">' + res.data[i].code +
                                '</option>';
                        }
                        code.innerHTML = option;
                    } else {
                        alert('Request failed.  Returned status of ' + xhr.status);
                    }
                };
                xhr.send();

            });
            code.addEventListener('change', function() {
                formes.submit();
            });
            cell.addEventListener('change', function() {
                formes.submit();
            });
            shift.addEventListener('change', function() {
                formes.submit();
            });
            barang.addEventListener('change', function() {
                formes.submit();
            });
        </script>
    @endsection
</x-app-layout>
