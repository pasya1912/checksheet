@extends('checksheet.checkdata.layout')
@section('header')
    {{ __('Checksheet Data List') }}
@endsection
@section('content')
    <form action="{{ route('checksheet.data') }}" method="GET" id="search-checksheet" class="text-center">

        <div class="flex flex-wrap gap-2 w-full  justify-center lg:justify-end text-start my-5">
            <div class="w-full md:w-5/12 lg:w-2/12 ">
                <label for="min_tanggal">From</label>
                <input type="date" id="min_tanggal" name="min_tanggal" class="form-input block w-full"
                    max="{{ request()->get('max_tanggal') }}" value="{{ request()->get('min_tanggal') }}">
            </div>
            <div class="w-full md:w-5/12 lg:w-2/12">
                <label for="max_tanggal">To</label>
                <input type="date" id="max_tanggal" name="max_tanggal" class="form-input block w-full"
                    min="{{ request()->get('min_tanggal') }}" value="{{ request()->get('max_tanggal') }}">
            </div>
        </div>
        <div class="flex flex-wrap gap-2 w-full  justify-center  my-5">
            <div class="w-full md:w-5/12 lg:w-1/12">
                <label for="line">Line</label>
                <select id="line" name="line" class=" w-full border-gray-400 p-2 rounded-lg" required>
                    <option value="" selected>Line</option>
                    @foreach ($lineList as $line)
                        <option value="{{ $line->line }}" {{ request()->get('line') == $line->line ? 'selected' : '' }}>
                            {{ $line->line }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-5/12 lg:w-1/12">
                <label for="code">Model</label>
                <select id="code" name="code" class="w-full border-gray-400 p-2 rounded-lg" required>
                    <option value="" selected>Model</option>
                    @foreach ($codeList as $code)
                        <option value="{{ $code->code }}" {{ request()->get('code') == $code->code ? 'selected' : '' }}>
                            {{ $code->code }}</option>
                    @endforeach

                </select>
            </div>
            <div class="w-full md:w-5/12 lg:w-3/12">
                <label for="checksheet">Checksheet</label>
                <select id="checksheet" name="checksheet" class="w-full border-gray-400 p-2 rounded-lg" required>
                    <option value="" selected>Checksheet</option>
                    @foreach ($checkList as $checksheet)
                        <option value="{{ $checksheet->id }}"
                            {{ request()->get('checksheet') == $checksheet->id ? 'selected' : '' }}>
                            {{ $checksheet->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-5/12 lg:w-3/12">
                <label for="area">Area</label>
                <select id="area" name="area" class="w-full border-gray-400 p-2 rounded-lg" required>
                    <option value="" selected>Area</option>
                    @foreach ($areaList as $area)
                        <option value="{{ $area->id }}" {{ request()->get('area') == $area->id ? 'selected' : '' }}>
                            {{ $area->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-full md:w-5/12 lg:w-1/12">
                <label for="cell">Cell</label>
                <select id="cell" name="cell" class="w-full border-gray-400 p-2 rounded-lg" required>
                    <option value="" selected>Cell</option>
                    <option value="m1" {{ request()->get('cell') == 'm1' ? 'selected' : '' }}>m1</option>
                    <option value="m2" {{ request()->get('cell') == 'm2' ? 'selected' : '' }}>m2</option>
                </select>
            </div>
            <div class="w-full md:w-5/12 lg:w-36">
                <label for="shift">Shift</label>
                <select id="shift" name="shift" class="w-full border-gray-400 p-2 rounded-lg" required>
                    <option value="" selected>Shift</option>
                    <option value="1" {{ request()->get('shift') == '1' ? 'selected' : '' }}>1</option>
                    <option value="2" {{ request()->get('shift') == '2' ? 'selected' : '' }}>2</option>
                    <option value="3" {{ request()->get('shift') == '3' ? 'selected' : '' }}>3</option>
                    <option value="1-long" {{ request()->get('shift') == '1-long' ? 'selected' : '' }}>1-long</option>
                    <option value="3-long" {{ request()->get('shift') == '3-long' ? 'selected' : '' }}>3-long</option>

                </select>
            </div>
            <div class="w-full md:w-5/12 lg:w-1/12">
                <label for="barang">First/last</label>
                <select id="barang" name="barang" class="w-full border-gray-400 p-2 rounded-lg" required>
                    <option value="" selected>First/last</option>
                    <option value="first" {{ request()->get('barang') == 'first' ? 'selected' : '' }}>first</option>
                    <option value="last" {{ request()->get('barang') == 'last' ? 'selected' : '' }}>last</option>
                </select>
            </div>


        </div>
    </form>
    <div class="w-full overflow-x-scroll">
        <table class="table-auto w-full table-layout-fixed border border-red-500 text-center">
            <colgroup>
                <col class="w-2/6">
                <col class="w-1/12">
                <col class="w-1/5">
                <col class="w-2/12">
                <col class="w-2/12">
            </colgroup>
            <thead class="border border-gray-400">
                <tr>
                    <th>Location</th>
                    <th>Shift</th>
                    <th>Tanggal</th>
                    <th>Value</th>
                    <th>Checker</th>
                </tr>
            </thead>
            <tbody class="border border-gray-200 ">
                @foreach ($checkdata['data'] as $data)
                    @if ($data->status == 'good')
                        <tr class="hover:bg-green-500 bg-green-300 border border-gray-300">
                        @elseif($data->status == 'notgood')
                        <tr class="hover:bg-red-500 bg-red-300 border border-gray-300">
                    @endif
                    <td class="py-5 border border-gray-300"><span class="text text-sm">
                            <div>{{ $data->line }}|{{ $data->code }}</div>
                            <div>{{ $data->nama_checksheet }}|{{ $data->nama_checkarea }}</div>
                            <div>{{ $data->nama }}</div>
                        </span></td>
                    <td class="py-5 border border-gray-300">{{ $data->shift }} ({{ $data->barang }})</td>

                    <td class="py-5 border border-gray-300 ">
                        {{ $data->tanggal }}
                    </td>
                    <td class="py-5 border border-gray-300">
                        <div class="flex flex-col">
                            <div class="w-11/12 md:w-1/2 m-auto "><span
                                    class="w-full px-5 py-1 text-gray-800 bg-gray-200 rounded-xl inline-block">{{ $data->value }}</span></div>
                            <div class="w-11/12 md:w-1/2 m-auto  flex items-center  ">
                                <span class=" ml-1 px-2 w-11/12 text-center py-1 text-xs font-semibold rounded-md">
                                    {{ $data->status }}</span>
                            </div>
                        </div>

                    </td>
                    <td class="py-5 border border-gray-300">
                        <div class=" flex flex-col ">
                            <div class="w-10/12 m-auto px-2 py-1">

                                <div class=" b-slate-500 text-gray-200 rounded-md bg-gray-400">
                                    <div class="text-xs md:text-sm block mb-1">{{ $data->name }}
                                    </div>
                                    <div class="text-xs md:text-sm block mt-1">
                                        ({{ $data->npk }})
                                    </div>
                                </div>



                            </div>
                            @if (auth()->user()->role == 'admin')
                                <div class="w-3/4 md:w-3/4 m-auto flex items-center  ">
                                    @include('checksheet.checkdata.changeStatus')
                                </div>
                            @else
                                @if ($data->approval == '4')
                                    <div class="w-3/4 md:w-1/2m-auto flex items-center">
                                        <span
                                            class="inline-block ml-1 px-2 py-1 w-11/12 text-center text-sm font-semibold text-gray-800 bg-green-200 rounded-md">
                                            Approved
                                        </span>
                                    </div>
                                @elseif($data->approval < 1 && $data->approval > 4)
                                    <div class="w-3/4 md:w-1/2 m-auto flex items-center">
                                        <span
                                            class="inline-block ml-1 px-2 py-1 w-11/12 text-center text-sm font-semibold text-gray-800 bg-red-200 rounded-md">
                                            Rejected
                                        </span>
                                    </div>
                                @else
                                    <div class="w-3/4 md:w-1/2 m-auto flex items-center ">
                                        <span
                                            class="inline-block ml-1 px-2 w-11/12 text-center py-1 text-sm font-semibold text-gray-800 bg-yellow-200 rounded-md">
                                            Waiting
                                        </span>
                                    </div>
                                @endif
                            @endif

                        </div>
                    </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

@endsection
@section('script')
    <script>
        var formes = document.getElementById('search-checksheet');



        formes.addEventListener('change', function() {
            formes.submit();
        });

        var min = document.querySelector('#min_tanggal');
        min.addEventListener('change', function() {
            formes.submit();
        });
        //================================================
        @if (auth()->user()->role == 'admin')


            function changeStatus(element){
                var id = element.getAttribute('data-id');
                var status = element.value;
                var url = "{{ route('checksheet.data.changeStatus', ':id') }}";
                url = url.replace(':id', id);
                var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                var data = {
                    status: status,
                    _token: token
                };
                fetch(url, {
                        method: 'POST',
                        body: JSON.stringify(data),
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                        if (data.status == 'success') {
                            alert('Status berhasil diubah');
                            location.reload();
                        } else {
                            alert('Status gagal diubah');
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }

        @endif
    </script>
@endsection
