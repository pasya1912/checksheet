@section('title', 'Cari')
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

                    <form action="{{ route('checksheet.setting') }}" method="GET" id="search-checksheet"
                        class="flex gap-3 w-full md:w-6/12">
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
                    </form>

                </div>
                <div class="w-full overflow-scroll">
                    <table class="table-auto w-full  border border-red-500 text-center">
                        <thead class="border border-red-400">
                            <tr>
                                <th>Line</th>
                                <th>Code</th>
                                <th>Nama</th>
                            </tr>
                        </thead>
                        <tbody class="border border-red-200 ">
                            @if (!empty($checkList['data']))
                                @foreach ($checkList['data'] as $check)
                                    <tr
                                        class="transition duration-300 ease-in-out py-5 border border-gray-700">

                                        <td class="py-5"><a onload="changeid(this)"
                                                href="{{ route('checksheet.setting.area', $check->id) }}?{{ request()->getQueryString() }}  ">{{ $check->line }}</a>
                                        </td>
                                        <td class="py-5"><a onload="changeid(this)"
                                                href="{{ route('checksheet.setting.area', $check->id) }}?{{ request()->getQueryString() }}  ">{{ $check->code }}</a>
                                        </td>
                                        <td class="py-5">
                                            <div class="w-full">
                                                <a onload="changeid(this)"
                                                    href="{{ route('checksheet.setting.area', $check->id) }}?{{ request()->getQueryString() }}  ">{{ $check->nama }}</a>
                                            </div>
                                            <div class="w-full">
                                                <span
                                                    class="bg-gray-200 rounded-md px-3 py-1 shadow-gray-50 shadow-sm">{{ $check->all }}
                                                    area</span>
                                            </div>
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
            //get selected value from selected option form then build a query string
            var line = document.getElementById('line');
            var code = document.getElementById('code');
            var cell = document.getElementById('cell');
            var shift = document.getElementById('shift');
            var barang = document.getElementById('barang');

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
