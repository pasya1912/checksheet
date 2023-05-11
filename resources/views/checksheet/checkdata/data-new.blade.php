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
        <table class="table-auto w-full table-layout-fixed border border-red-500">
            <tbody class="border-gray-200 border-solid">
                @foreach ($checkdata['data'] as $data)
                    <tr class="relative border border-gray-300">
                        <td class="relative w-11/12 m-auto block my-2">
                            <div class="flex justify-evenly border border-gray-300">
                                <div class="w-3/12 ">
                                    {{ $data->line }}
                                </div>
                                <div>
                                    {{ $data->code }}
                                </div>
                            </div>
                        </td>
                        <td class="relative w-11/12 m-auto block my-2 ">
                            <div class="flex ">
                                <div class="w-3/12">
                                    Checksheet
                                </div>
                                <div>{{ $data->nama_checksheet }}</div>
                            </div>
                            <div class="flex ">
                                <div class="w-3/12 ">
                                    Area
                                </div>
                                <div>{{ $data->nama_checkarea }}</div>
                            </div>
                            <div class="flex ">
                                <div class="w-3/12 ">
                                    Cell
                                </div>
                                <div>{{ $data->nama }}</div>
                            </div>
                            <div class="flex ">
                                <div class="w-3/12 ">
                                    Shift
                                </div>
                                <div>{{ $data->shift }} ({{ $data->barang }})</div>
                            </div>
                            <div class="flex ">
                                <div class="w-3/12 ">
                                    Tanggal
                                </div>
                                <div>{{ $data->tanggal }}</div>
                            </div>
                        </td>
                        <td class="relative w-11/12 h-auto m-auto block my-2">
                            <div class="relative flex flex-wrap">
                                <div class="w-3/12">
                                    Value
                                </div>
                                <div class="max-h-sm flex flex-wrap gap-1">
                                    <div class="max-h-screen">
                                        {{ $data->value }}
                                        <span
                                            class="bg-gray-300 rounded-sm shadow-md shadow-black {{ $data->status == 'notgood' ? 'bg-red-300' : 'bg-green-300' }}  py-0.5 px-5">{{ $data->status == 'notgood' ? 'NG' : 'OK' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @if ($data->notes != null || $data->notes != '')
                        <?php
                        $data->notes = explode('<br>', $data->notes)[0];
                        $data->notes = htmlspecialchars($data->notes);

                        ?>
                        <td class="relative w-11/12 h-auto m-auto block my-2">
                            <div class="relative flex flex-wrap">
                                <div class="w-3/12">
                                    Notes
                                </div>
                                <div class="max-h-sm flex flex-wrap gap-1">
                                    <div class="max-h-screen max-w-full py-0.5 pr-2 bg-gray-100 rounded-sm">
                                        {{ $data->notes }}
                                    </div>
                                    @if ($data->mark != null || $data->mark != '')
                                        <div>
                                            @if ($data->mark == 1)
                                                <span
                                                    class=" rounded-sm shadow-md shadow-black bg-green-300  py-0.5 px-5">Problem
                                                    Solved</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        @if($data->mark != null || $data->mark ==1)
                        <td class="relative w-11/12 h-auto m-auto block my-2">
                            <div class="relative flex flex-wrap">
                                <div class="w-3/12">
                                    Revised Value
                                </div>
                                <div class="max-h-sm flex flex-wrap gap-1">
                                    <div class="max-h-screen">
                                        {{ $data->revised_value }}
                                        <span
                                            class="bg-gray-300 rounded-sm shadow-md shadow-black {{ $data->revised_status == 'notgood' ? 'bg-red-300' : 'bg-green-300' }}  py-0.5 px-5">{{ $data->revised_status == 'notgood' ? 'NG' : 'OK' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        @endif
                        @endif
                        <td class="relative w-11/12 h-auto m-auto block my-2">
                            <div class="relative flex flex-wrap">
                                <div class="w-3/12">
                                    Checker
                                </div>
                                <div class="max-h-sm flex flex-wrap gap-1">
                                    <div class="max-h-screen">
                                        {{ $data->name }}
                                        <span
                                            class="bg-gray-300 rounded-sm shadow-md shadow-black  py-0.5 px-5">{{$data->npk}}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="relative w-11/12 h-auto m-auto block my-2">
                            <div class="relative flex flex-wrap">
                                <div class="w-3/12">
                                    Approval
                                </div>
                                <div class=" w-full flex flex-wrap justify-between border border-gray-300 py-2 px-5">
                                    <div class="max-h-screen flex flex-col gap-5  items-center text-center">
                                        <div class="font-bold text-lg">GP</div>
                                        @if($data->approval >= 1)
                                        <div class="rounded-full w-5 h-5 bg-green-300"></div>
                                        @else
                                        <div class="rounded-full w-5 h-5 bg-gray-300"></div>
                                        @endif
                                    </div>
                                    <div class="max-h-screen flex flex-col gap-5 items-center text-center">
                                        <div class="font-bold text-lg">Leader</div>
                                        @if($data->approval >= 2)
                                        <div class="rounded-full w-5 h-5 bg-green-300"></div>
                                        @else
                                        <div class="rounded-full w-5 h-5 bg-gray-300"></div>
                                        @endif
                                    </div>
                                    <div class="max-h-screen flex flex-col gap-5  items-center text-center">
                                        <div class="font-bold text-lg">Supervisor</div>
                                        @if($data->approval >= 3)
                                        <div class="rounded-full w-5 h-5 bg-green-300"></div>
                                        @else
                                        <div class="rounded-full w-5 h-5 bg-gray-300"></div>
                                        @endif
                                    </div>
                                    <div class="max-h-screen flex flex-col gap-5  items-center text-center">
                                        <div class="font-bold text-lg">Manager</div>
                                        @if($data->approval >= 4)
                                        <div class="rounded-full w-5 h-5 bg-green-300"></div>
                                        @else
                                        <div class="rounded-full w-5 h-5 bg-gray-300"></div>
                                        @endif
                                    </div>
                                </div>
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


            function changeStatus(element) {
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
